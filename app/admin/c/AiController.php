<?php

// +----------------------------------------------------------------------
// | JiZhiCMS { 极致CMS，给您极致的建站体验 }
// +----------------------------------------------------------------------
// | AI 助手模块：多平台大模型接入 + 流式输出 + 工具调用（文件读写 / 数据库增删查改 / 备份 / 插件生成）
// +----------------------------------------------------------------------

namespace app\admin\c;

/**
 * AI 助手控制器
 * 说明：
 * 1. 平台配置统一存储在 sysconfig 表 field=ai_config 中（JSON）。
 * 2. 兼容所有「OpenAI 接口规范」的大模型平台（OpenAI / DeepSeek / 通义千问 / 智谱 / Moonshot 等）。
 * 3. 流式输出（SSE）+ Function Calling（tools）实现文件与数据库的增删查改。
 * 4. 权限模式：confirm（关键操作需确认）/ full（完全控制）。
 * 5. 会话缓存于 cache/ai/*.php，可跨设备查看。
 */
class AiController extends CommonController
{
    // 禁止写入的目录（系统 PHP 文件），AI 只能读取、不能修改
    private $protectDirs = array('frphp/', 'app/admin/c/', 'app/admin/t/', 'app/home/c/', 'install/');
    // 禁止写入的入口/配置文件
    private $protectFiles = array('index.php', 'admin.php', 'conf/config.php', '.htaccess', 'web.config', 'robots.txt');

    // ------------------------------------------------------------------
    //  页面
    // ------------------------------------------------------------------

    // 对话主界面
    public function index()
    {
        $cfg = $this->getAiConfig();
        $this->isadmin = ($_SESSION['admin']['isadmin'] == 1) ? 1 : 0;
        $this->platforms = isset($cfg['platforms']) ? $cfg['platforms'] : array();
        $this->active = isset($cfg['active']) ? (int)$cfg['active'] : 0;
        $this->mode = isset($cfg['mode']) ? $cfg['mode'] : 'confirm';
        $this->display('ai-index');
    }

    // 可视化页面编辑
    public function visual()
    {
        $cfg = $this->getAiConfig();
        $this->isadmin = ($_SESSION['admin']['isadmin'] == 1) ? 1 : 0;
        $this->platforms = isset($cfg['platforms']) ? $cfg['platforms'] : array();
        $this->active = isset($cfg['active']) ? (int)$cfg['active'] : 0;
        $this->mode = isset($cfg['mode']) ? $cfg['mode'] : 'confirm';
        $this->homeurl = get_domain() . '/';
        $this->display('ai-visual');
    }

    // 平台配置界面
    public function config()
    {
        if ($_SESSION['admin']['isadmin'] != 1) {
            Error(JZLANG('非超级管理员无法配置 AI 助手！'), U('Index/welcome'));
        }
        $cfg = $this->getAiConfig();
        $this->cfg = $cfg;
        $this->platforms = isset($cfg['platforms']) ? $cfg['platforms'] : array();
        $this->active = isset($cfg['active']) ? (int)$cfg['active'] : 0;
        $this->memory = $this->memoryGet();
        $this->display('ai-config');
    }

    // 安装/初始化：注册后台菜单与权限
    public function install()
    {
        if ($_SESSION['admin']['isadmin'] != 1) {
            JsonReturn(array('code' => 1, 'msg' => JZLANG('仅超级管理员可执行初始化！')));
        }
        // 1. 注册权限项
        $top = M('ruler')->find(array('name' => 'AI 助手', 'pid' => 0));
        if (!$top) {
            $topId = M('ruler')->add(array('name' => 'AI 助手', 'fc' => 'Ai', 'pid' => 0, 'isdesktop' => 0, 'sys' => 1));
        } else {
            $topId = $top['id'];
        }
        $menuItems = array(
            array('name' => 'AI 助手', 'fc' => 'Ai/index', 'isdesktop' => 1),
            array('name' => '页面可视化', 'fc' => 'Ai/visual', 'isdesktop' => 1),
            array('name' => 'AI 配置', 'fc' => 'Ai/config', 'isdesktop' => 1),
        );
        $ids = array();
        foreach ($menuItems as $m) {
            $r = M('ruler')->find(array('fc' => $m['fc']));
            if ($r) {
                $ids[] = $r['id'];
            } else {
                $ids[] = M('ruler')->add(array('name' => $m['name'], 'fc' => $m['fc'], 'pid' => $topId, 'isdesktop' => $m['isdesktop'], 'sys' => 1));
            }
        }
        // 2. 把「AI 助手」加入默认桌面左侧菜单
        $layout = M('layout')->find(array('isdefault' => 1));
        if ($layout) {
            $left = json_decode($layout['left_layout'], true);
            if (!is_array($left)) $left = array();
            $exists = false;
            foreach ($left as $group) {
                if (isset($group['name']) && $group['name'] == 'AI 助手') { $exists = true; break; }
            }
            if (!$exists) {
                $left[] = array(
                    'name' => 'AI 助手',
                    'icon' => '&#xe6ce;',
                    'nav' => array(
                        array('key' => (string)mt_rand(10000, 99999), 'title' => 'AI 助手', 'value' => (string)$ids[0], 'icon' => ''),
                        array('key' => (string)mt_rand(10000, 99999), 'title' => '页面可视化', 'value' => (string)$ids[1], 'icon' => ''),
                        array('key' => (string)mt_rand(10000, 99999), 'title' => 'AI 配置', 'value' => (string)$ids[2], 'icon' => ''),
                    ),
                );
                M('layout')->update(array('id' => $layout['id']), array('left_layout' => json_encode($left, JSON_UNESCAPED_UNICODE)));
            }
        }
        JsonReturn(array('code' => 0, 'msg' => JZLANG('初始化成功，请刷新后台首页查看左侧菜单「AI 助手」！')));
    }

    // ------------------------------------------------------------------
    //  配置
    // ------------------------------------------------------------------

    private function defaultConfig()
    {
        return array(
            'platforms' => array(),
            'active' => 0,
            'temperature' => 0.3,
            'max_tokens' => 4096,
            'max_steps' => 20,
            'mode' => 'confirm',
        );
    }

    private function getAiConfig()
    {
        static $cache = null;
        if ($cache !== null) return $cache;
        $row = M('sysconfig')->find(array('field' => 'ai_config'));
        $cfg = $row ? json_decode($row['data'], true) : null;
        if (!is_array($cfg)) $cfg = array();
        $cache = array_merge($this->defaultConfig(), $cfg);
        return $cache;
    }

    // 保存配置（仅超管）
    public function saveconfig()
    {
        if ($_SESSION['admin']['isadmin'] != 1) {
            JsonReturn(array('code' => 1, 'msg' => JZLANG('仅超级管理员可操作！')));
        }
        $in = $this->jsonInput();
        $platforms = array();
        if (isset($in['platforms']) && is_array($in['platforms'])) {
            foreach ($in['platforms'] as $p) {
                $name = isset($p['name']) ? trim($p['name']) : '';
                $base = isset($p['base_url']) ? trim($p['base_url']) : '';
                if ($name === '' || $base === '') continue;
                $platforms[] = array(
                    'name' => $name,
                    'base_url' => rtrim($base, '/'),
                    'api_key' => isset($p['api_key']) ? trim($p['api_key']) : '',
                    'model' => isset($p['model']) ? trim($p['model']) : '',
                );
            }
        }
        $cfg = $this->defaultConfig();
        $cfg['platforms'] = $platforms;
        $cfg['active'] = isset($in['active']) ? (int)$in['active'] : 0;
        $cfg['temperature'] = isset($in['temperature']) ? (float)$in['temperature'] : 0.3;
        $cfg['max_tokens'] = isset($in['max_tokens']) ? (int)$in['max_tokens'] : 4096;
        $cfg['max_steps'] = isset($in['max_steps']) ? (int)$in['max_steps'] : 20;
        if (isset($in['mode']) && $in['mode'] === 'full') $cfg['mode'] = 'full';
        if ($cfg['active'] >= count($platforms)) $cfg['active'] = 0;

        $data = json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $row = M('sysconfig')->find(array('field' => 'ai_config'));
        if ($row) {
            M('sysconfig')->update(array('field' => 'ai_config'), array('data' => $data));
        } else {
            M('sysconfig')->add(array('title' => JZLANG('AI 助手配置'), 'field' => 'ai_config', 'type' => 3, 'data' => $data, 'typeid' => 0));
        }
        setCache('webconfig', null);
        JsonReturn(array('code' => 0, 'msg' => JZLANG('保存成功！')));
    }

    // 测试平台连接（仅超管）：向 {base_url}/chat/completions 发一次最小请求
    public function testconn()
    {
        if ($_SESSION['admin']['isadmin'] != 1) {
            JsonReturn(array('code' => 1, 'msg' => JZLANG('仅超级管理员可操作！')));
        }
        $in = $this->jsonInput();
        $base = isset($in['base_url']) ? trim($in['base_url']) : '';
        $key = isset($in['api_key']) ? trim($in['api_key']) : '';
        $model = isset($in['model']) ? trim($in['model']) : '';
        if ($base === '' || $key === '' || $model === '') {
            JsonReturn(array('code' => 1, 'msg' => JZLANG('请先填写 base_url、api_key、model 三项！')));
        }
        $url = rtrim($base, '/') . '/chat/completions';
        $body = array(
            'model' => $model,
            'messages' => array(array('role' => 'user', 'content' => 'ping')),
            'max_tokens' => 8,
            'stream' => false,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($res === false) {
            JsonReturn(array('code' => 1, 'msg' => JZLANG('请求失败：') . $err . JZLANG('（请检查 base_url 是否正确、服务器能否访问外网）'), 'url' => $url));
        }
        $json = json_decode($res, true);
        if ($code >= 200 && $code < 300 && is_array($json) && !isset($json['error'])) {
            $reply = isset($json['choices'][0]['message']['content']) ? $json['choices'][0]['message']['content'] : '';
            $streamOk = $this->probeStream($url, $key, $model);
            JsonReturn(array('code' => 0, 'msg' => JZLANG('连接成功！'), 'reply' => $reply, 'url' => $url, 'http' => $code, 'stream' => $streamOk));
        }
        $emsg = '';
        if (is_array($json) && isset($json['error'])) {
            $e = $json['error'];
            $emsg = is_array($e) ? (isset($e['message']) ? $e['message'] : json_encode($e, JSON_UNESCAPED_UNICODE)) : $e;
        }
        if ($emsg === '') $emsg = mb_substr((string)$res, 0, 300);
        JsonReturn(array('code' => 1, 'msg' => JZLANG('连接失败（HTTP ') . $code . '）：' . $emsg, 'url' => $url, 'http' => $code));
    }

    // 探测平台是否支持流式（SSE）输出
    private function probeStream($url, $key, $model)
    {
        $body = array(
            'model' => $model,
            'messages' => array(array('role' => 'user', 'content' => 'ping')),
            'max_tokens' => 8,
            'stream' => true,
        );
        $got = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: text/event-stream', 'Authorization: Bearer ' . $key));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($c, $d) use (&$got) {
            $got .= $d;
            return strlen($d);
        });
        curl_exec($ch);
        curl_close($ch);
        return strpos($got, 'data:') !== false;
    }

    // ------------------------------------------------------------------
    //  记忆与指引（存于 sysconfig，可在「AI 配置」页查看/编辑/清空）
    //  用途：记录用户画像/偏好，以及「哪里可以找什么」的指引，注入系统提示词
    // ------------------------------------------------------------------

    // 读取 sysconfig 中 JSON 形式的配置
    private function sysGet($field)
    {
        $row = M('sysconfig')->find(array('field' => $field));
        if (!$row || !isset($row['data'])) return null;
        $d = json_decode($row['data'], true);
        return is_array($d) ? $d : null;
    }

    // 写入 sysconfig 中 JSON 形式的配置
    private function sysSet($field, $title, $arr)
    {
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $row = M('sysconfig')->find(array('field' => $field));
        if ($row) {
            M('sysconfig')->update(array('field' => $field), array('data' => $data));
        } else {
            M('sysconfig')->add(array('title' => $title, 'field' => $field, 'type' => 3, 'data' => $data, 'typeid' => 0));
        }
        setCache('webconfig', null);
    }

    private function memoryGet()
    {
        $m = $this->sysGet('ai_memory');
        if (!is_array($m) || !isset($m['items']) || !is_array($m['items'])) $m = array('items' => array());
        return $m;
    }

    // 组装用户记忆文本（注入系统提示词）
    private function memoryText()
    {
        $m = $this->memoryGet();
        $lines = array();
        foreach ($m['items'] as $it) {
            $t = is_array($it) ? (isset($it['text']) ? $it['text'] : '') : (string)$it;
            $t = trim($t);
            if ($t !== '') $lines[] = '- ' . $t;
        }
        if (!$lines) return '';
        return "【记忆与指引】（本站在「AI 配置」中维护的长期信息：用户画像/偏好，以及「哪里可以找什么」的指引。回答时遵循，已知信息不要重复询问；据此定位文件与数据，避免盲目探索）\n" . implode("\n", $lines);
    }

    // 工具：记录用户记忆
    private function toolRememberUser($content)
    {
        $content = trim((string)$content);
        if ($content === '') return JZLANG('记忆内容为空！');
        $m = $this->memoryGet();
        foreach ($m['items'] as $it) {
            $t = is_array($it) ? (isset($it['text']) ? $it['text'] : '') : (string)$it;
            if (trim($t) === $content) return array('ok' => true, 'msg' => JZLANG('该记忆已存在，无需重复记录'));
        }
        $m['items'][] = array('text' => $content, 'time' => time(), 'by' => isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : '');
        if (count($m['items']) > 100) $m['items'] = array_slice($m['items'], -100);
        $m['updated'] = time();
        $this->sysSet('ai_memory', JZLANG('AI 记忆与指引'), $m);
        $this->logAction('remember_user', array('content' => $content));
        return array('ok' => true, 'msg' => JZLANG('已记住'));
    }

    // 配置页：保存记忆/指引（每行一条）
    public function savememory()
    {
        if ($_SESSION['admin']['isadmin'] != 1) JsonReturn(array('code' => 1, 'msg' => JZLANG('仅超级管理员可操作！')));
        $in = $this->jsonInput();
        $raw = isset($in['text']) ? (string)$in['text'] : '';
        $items = array();
        foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $items[] = array('text' => $line, 'time' => time(), 'by' => 'manual');
        }
        $this->sysSet('ai_memory', JZLANG('AI 记忆与指引'), array('items' => $items, 'updated' => time()));
        JsonReturn(array('code' => 0, 'msg' => JZLANG('已保存 ') . count($items) . JZLANG(' 条')));
    }

    // 配置页：清空记忆/指引
    public function clearmemory()
    {
        if ($_SESSION['admin']['isadmin'] != 1) JsonReturn(array('code' => 1, 'msg' => JZLANG('仅超级管理员可操作！')));
        M('sysconfig')->delete(array('field' => 'ai_memory'));
        setCache('webconfig', null);
        JsonReturn(array('code' => 0, 'msg' => JZLANG('已清空')));
    }

    // ------------------------------------------------------------------
    //  会话缓存（cache/ai/*.php，可跨设备查看）
    // ------------------------------------------------------------------

    private function convDir()
    {
        $d = Cache_Path . '/ai';
        if (!is_dir($d)) @mkdir($d, 0777, true);
        return $d;
    }

    private function convIdSafe($id)
    {
        return (is_string($id) && preg_match('/^[A-Za-z0-9_\-]{6,64}$/', $id)) ? $id : false;
    }

    private function convNew($title = '新会话')
    {
        $id = date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8);
        return array(
            'id' => $id,
            'title' => $title,
            'admin' => isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : '',
            'created' => time(),
            'updated' => time(),
            'messages' => array(),
            'display' => array(),
            'pending' => array(),
            'turns' => array(),
        );
    }

    // 取会话轮次；旧会话（无 turns 记录）按 display/messages 里的用户消息临时合成，
    // 保证任何能对话的用户都能使用「回滚对话」
    private function turnsOf($conv)
    {
        if (!empty($conv['turns']) && is_array($conv['turns'])) return $conv['turns'];
        $turns = array();
        $msgs = (isset($conv['messages']) && is_array($conv['messages'])) ? $conv['messages'] : array();
        $display = (isset($conv['display']) && is_array($conv['display'])) ? $conv['display'] : array();
        $mi = array();
        foreach ($msgs as $i => $m) { if (isset($m['role']) && $m['role'] === 'user') $mi[] = $i; }
        $k = 0;
        foreach ($display as $i => $it) {
            if (isset($it['role']) && $it['role'] === 'user') {
                $turns[] = array(
                    'user' => isset($it['content']) ? $it['content'] : '',
                    'display_index' => $i,
                    'msg_index' => isset($mi[$k]) ? $mi[$k] : 0,
                    'ops' => array(),
                    'legacy' => 1,
                );
                $k++;
            }
        }
        return $turns;
    }

    // 给 display 中每轮的用户消息标注轮次序号（供前端渲染「回滚」按钮）
    private function displayWithTurns($conv)
    {
        $display = (isset($conv['display']) && is_array($conv['display'])) ? $conv['display'] : array();
        foreach ($this->turnsOf($conv) as $ti => $t) {
            $di = isset($t['display_index']) ? (int)$t['display_index'] : -1;
            if ($di >= 0 && isset($display[$di]) && isset($display[$di]['role']) && $display[$di]['role'] === 'user') {
                $display[$di]['turn'] = $ti;
            }
        }
        return $display;
    }

    private function convLoad($id)
    {
        $id = $this->convIdSafe($id);
        if (!$id) return null;
        $f = $this->convDir() . '/' . $id . '.php';
        if (!is_file($f)) return null;
        $txt = @file_get_contents($f);
        if ($txt === false) return null;
        $d = json_decode(substr($txt, 14), true);
        return is_array($d) ? $d : null;
    }

    private function convSave($conv)
    {
        $id = $this->convIdSafe(isset($conv['id']) ? $conv['id'] : '');
        if (!$id) return;
        $conv['updated'] = time();
        $txt = '<?php die();?>' . json_encode($conv, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        @file_put_contents($this->convDir() . '/' . $id . '.php', $txt, LOCK_EX);
    }

    private function convList()
    {
        $out = array();
        foreach (glob($this->convDir() . '/*.php') as $f) {
            $txt = @file_get_contents($f);
            if ($txt === false) continue;
            $d = json_decode(substr($txt, 14), true);
            if (!is_array($d) || empty($d['id'])) continue;
            $out[] = array(
                'id' => $d['id'],
                'title' => isset($d['title']) ? $d['title'] : '会话',
                'admin' => isset($d['admin']) ? $d['admin'] : '',
                'updated' => isset($d['updated']) ? $d['updated'] : 0,
                'count' => isset($d['display']) ? count($d['display']) : 0,
            );
        }
        usort($out, function ($a, $b) { return $b['updated'] - $a['updated']; });
        return array_slice($out, 0, 100);
    }

    // 会话列表
    public function conversations()
    {
        JsonReturn(array('code' => 0, 'data' => $this->convList()));
    }

    // 加载某个会话
    public function loadchat()
    {
        $conv = $this->convLoad($this->frparam('id', 1));
        if (!$conv) JsonReturn(array('code' => 1, 'msg' => JZLANG('会话不存在！')));
        JsonReturn(array('code' => 0, 'data' => array(
            'id' => $conv['id'],
            'title' => $conv['title'],
            'display' => $this->displayWithTurns($conv),
            'awaiting' => !empty($conv['pending']),
            'pending' => $conv['pending'],
        )));
    }

    // 删除会话
    public function deletechat()
    {
        $id = $this->convIdSafe($this->frparam('id', 1));
        if ($id) {
            $f = $this->convDir() . '/' . $id . '.php';
            if (is_file($f)) @unlink($f);
        }
        JsonReturn(array('code' => 0, 'msg' => JZLANG('已删除')));
    }

    // 回滚：撤销指定轮次（含之后）AI 对文件/数据库的改动，并删除对应对话
    public function rollback()
    {
        $in = $this->jsonInput();
        $conv = $this->convLoad(isset($in['conversation_id']) ? $in['conversation_id'] : '');
        if (!$conv) JsonReturn(array('code' => 1, 'msg' => JZLANG('会话不存在！')));
        $turns = $this->turnsOf($conv);
        if (empty($turns)) JsonReturn(array('code' => 1, 'msg' => JZLANG('暂无可回滚的对话轮次！')));
        $turn = isset($in['turn']) ? (int)$in['turn'] : count($turns) - 1;
        if ($turn < 0 || $turn >= count($turns)) JsonReturn(array('code' => 1, 'msg' => JZLANG('回滚位置无效！')));

        // 收集该轮及之后的所有改动，倒序撤销（后发生的先还原）
        $ops = array();
        for ($i = $turn; $i < count($turns); $i++) {
            if (!empty($turns[$i]['ops']) && is_array($turns[$i]['ops'])) {
                $ops = array_merge($ops, $turns[$i]['ops']);
            }
        }
        $ops = array_reverse($ops);
        $restored = array();
        $errors = array();
        foreach ($ops as $op) {
            $type = isset($op['type']) ? $op['type'] : '';
            if ($type === 'file_restore') {
                $backup = $this->safePath($op['backup'], true);
                $dst = $this->safePath($op['rel'], false);
                if ($backup && $dst && @copy($backup, $dst)) $restored[] = $op['rel'];
                else $errors[] = $op['rel'];
            } elseif ($type === 'file_delete') {
                $dst = $this->safePath($op['rel'], true);
                if ($dst && is_file($dst)) { @unlink($dst); $restored[] = $op['rel']; }
            } elseif ($type === 'db_restore') {
                $abs = $this->safePath($op['file'], true);
                $r = $abs ? $this->execSqlFile($abs) : JZLANG('快照文件不存在');
                if ($r === true) $restored[] = basename($op['file']);
                else $errors[] = basename($op['file']) . (is_string($r) ? '：' . $r : '');
            } elseif ($type === 'dir_delete') {
                $abs = $this->safePath($op['dir'], true);
                if ($abs && is_dir($abs)) { $this->rrmdir($abs); $restored[] = $op['dir']; }
            }
        }

        // 截断会话：删除该轮及之后的对话记录（含 AI 回复），保留该轮之前的上下文
        $di = isset($turns[$turn]['display_index']) ? (int)$turns[$turn]['display_index'] : 0;
        $mi = isset($turns[$turn]['msg_index']) ? (int)$turns[$turn]['msg_index'] : 0;
        $conv['display'] = array_slice($conv['display'], 0, $di);
        $conv['messages'] = array_slice($conv['messages'], 0, $mi);
        $conv['turns'] = array_slice($turns, 0, $turn);
        $conv['pending'] = array();
        $this->convSave($conv);
        $this->logAction('rollback', array('turn' => $turn, 'restored' => $restored, 'errors' => $errors));

        $msg = JZLANG('已回滚') . (empty($restored) ? JZLANG('，本轮无文件/数据改动') : JZLANG('，已还原 ') . count($restored) . JZLANG(' 项'));
        if (!empty($errors)) $msg .= JZLANG('，') . count($errors) . JZLANG(' 项还原失败');
        JsonReturn(array(
            'code' => 0,
            'msg' => $msg,
            'content' => isset($turns[$turn]['user']) ? $turns[$turn]['user'] : '',
            'errors' => $errors,
            'display' => $this->displayWithTurns($conv),
        ));
    }

    // 回滚辅助：执行快照 SQL 文件（含多条语句）
    private function execSqlFile($abs)
    {
        $sql = @file_get_contents($abs);
        if ($sql === false || trim($sql) === '') return JZLANG('快照文件为空');
        try {
            $pdo = new \PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::MYSQL_ATTR_MULTI_STATEMENTS => true)
            );
            $pdo->exec($sql);
            return true;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    // 回滚辅助：递归删除目录
    private function rrmdir($dir)
    {
        $items = @scandir($dir);
        if (!$items) return;
        foreach ($items as $it) {
            if ($it === '.' || $it === '..') continue;
            $full = $dir . '/' . $it;
            if (is_dir($full)) $this->rrmdir($full);
            else @unlink($full);
        }
        @rmdir($dir);
    }

    // ------------------------------------------------------------------
    //  流式对话（SSE）
    // ------------------------------------------------------------------

    private function sseStart()
    {
        @ini_set('zlib.output_compression', '0');
        @ini_set('output_buffering', '0');
        @ini_set('implicit_flush', '1');
        @ini_set('display_errors', '0');
        @set_time_limit(0);
        header('Content-Type: text/event-stream; charset=utf-8');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        while (ob_get_level() > 0) { @ob_end_flush(); }
        @ob_implicit_flush(true);
        // 释放会话锁，避免阻塞后续请求（如确认操作）
        if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE) {
            @session_write_close();
        }
    }

    private function sseSend($event, $data)
    {
        echo 'event: ' . $event . "\n";
        echo 'data: ' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\n";
        @flush();
    }

    private function sseEnd()
    {
        echo ": end\n\n";
        @flush();
    }

    // 注册「致命错误上报」：进程异常终止（内存溢出、超时、未捕获的致命错误等）时，
    // 尽力把原因推给前端并写入 backup/ai/log/，避免界面无声卡住、无从排查
    private function registerFatalReporter()
    {
        register_shutdown_function(function () {
            $e = error_get_last();
            if (!$e || !in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR), true)) return;
            $msg = $e['message'] . ' @ ' . $e['file'] . ':' . $e['line'];
            $dir = APP_PATH . 'backup/ai/log';
            if (!is_dir($dir)) @mkdir($dir, 0777, true);
            @file_put_contents($dir . '/fatal.log', date('Y-m-d H:i:s') . "\t" . $msg . "\n", FILE_APPEND);
            echo "event: error\n";
            echo 'data: ' . json_encode(array('msg' => JZLANG('后端处理中断（致命错误）：') . $msg), JSON_UNESCAPED_UNICODE) . "\n\n";
            echo "event: done\n";
            echo "data: {\"awaiting\":false}\n\n";
            @flush();
        });
    }

    // 进程被外部杀死（如 Web 服务器/FPM 超时、连接中断）时也留下痕迹，便于排查
    private function registerAbortReporter()
    {
        register_shutdown_function(function () {
            if (!function_exists('connection_aborted') || !connection_aborted()) return;
            $dir = APP_PATH . 'backup/ai/log';
            if (!is_dir($dir)) @mkdir($dir, 0777, true);
            @file_put_contents($dir . '/fatal.log', date('Y-m-d H:i:s') . "\t客户端连接中断（可能为服务器超时或手动停止）\n", FILE_APPEND);
        });
    }

    // 解析运行参数（平台、模式、写权限）
    private function resolveRuntime($in)
    {
        $cfg = $this->getAiConfig();
        if (empty($cfg['platforms'])) {
            return array('error' => JZLANG('请先在「AI 配置」中添加并选择一个大模型平台！'));
        }
        $active = isset($cfg['active']) ? (int)$cfg['active'] : 0;
        if (!isset($cfg['platforms'][$active])) $active = 0;
        $platform = $cfg['platforms'][$active];
        if (empty($platform['api_key']) || empty($platform['base_url']) || empty($platform['model'])) {
            return array('error' => JZLANG('当前平台的 api_key / base_url / model 未填写完整！'));
        }
        $mode = isset($in['mode']) ? $in['mode'] : (isset($cfg['mode']) ? $cfg['mode'] : 'confirm');
        return array(
            'cfg' => $cfg,
            'platform' => $platform,
            'mode' => ($mode === 'full') ? 'full' : 'confirm',
            'canWrite' => ($_SESSION['admin']['isadmin'] == 1),
        );
    }

    // 发起对话（流式）
    public function chat()
    {
        $this->sseStart();
        $this->registerFatalReporter();
        $this->registerAbortReporter();
        try {
            $in = $this->jsonInput();
            $ctx = $this->resolveRuntime($in);
            if (isset($ctx['error'])) {
                $this->sseSend('error', array('msg' => $ctx['error']));
                $this->sseSend('done', array('awaiting' => false));
                $this->sseEnd();
                return;
            }
            $conv = $this->convLoad(isset($in['conversation_id']) ? $in['conversation_id'] : '');
            if (!$conv) $conv = $this->convNew();
            $content = isset($in['content']) ? trim($in['content']) : '';
            // 空内容 = 「继续」：若还有待确认操作，必须先确认/取消，否则消息序列不完整会被平台拒绝
            if ($content === '' && !empty($conv['pending'])) {
                $this->sseSend('error', array('msg' => JZLANG('还有关键操作等待确认，请先点「确认」或「取消」再继续。')));
                $this->sseSend('done', array('awaiting' => true));
                $this->sseEnd();
                return;
            }
            $newTurn = null;
            if ($content !== '') {
                // 旧会话先补齐历史轮次（须在本条消息写入前），再追加本轮
                if (empty($conv['turns'])) $conv['turns'] = $this->turnsOf($conv);
                $conv['messages'][] = array('role' => 'user', 'content' => $content);
                $conv['display'][] = array('role' => 'user', 'content' => $content);
                $conv['turns'][] = array(
                    'user' => $content,
                    'display_index' => count($conv['display']) - 1,
                    'msg_index' => count($conv['messages']) - 1,
                    'ops' => array(),
                    'time' => time(),
                );
                $newTurn = count($conv['turns']) - 1;
                if (count($conv['messages']) === 1) $conv['title'] = mb_substr($content, 0, 24);
                $conv['pending'] = array();
            }
            $this->convSave($conv);
            $this->sseSend('conv', array('id' => $conv['id'], 'title' => $conv['title'], 'turn' => $newTurn));
            $context = isset($in['context']) ? $in['context'] : null;
            $this->agentStream($conv, $ctx['cfg'], $ctx['platform'], $ctx['mode'], $ctx['canWrite'], $context);
        } catch (\Throwable $e) {
            $this->sseSend('error', array('msg' => $e->getMessage()));
        }
        $this->sseEnd();
    }

    // 确认后继续（流式）
    public function confirm()
    {
        $this->sseStart();
        $this->registerFatalReporter();
        $this->registerAbortReporter();
        try {
            $in = $this->jsonInput();
            $ctx = $this->resolveRuntime($in);
            if (isset($ctx['error'])) {
                $this->sseSend('error', array('msg' => $ctx['error']));
                $this->sseSend('done', array('awaiting' => false));
                $this->sseEnd();
                return;
            }
            $conv = $this->convLoad(isset($in['conversation_id']) ? $in['conversation_id'] : '');
            if (!$conv || empty($conv['pending'])) {
                $this->sseSend('error', array('msg' => JZLANG('没有待确认的操作！')));
                $this->sseSend('done', array('awaiting' => false));
                $this->sseEnd();
                return;
            }
            $approve = !empty($in['approve']);
            if ($approve) {
                $this->execToolCalls($conv, $conv['pending'], $ctx['canWrite']);
            } else {
                foreach ($conv['pending'] as $tc) {
                    $msg = JZLANG('用户已拒绝执行该操作，请勿重复尝试。');
                    $conv['messages'][] = array('role' => 'tool', 'tool_call_id' => $tc['id'], 'content' => $msg);
                    $conv['display'][] = array('role' => 'action', 'tool' => $tc['name'], 'args' => $tc['args'], 'result' => $msg, 'status' => 'denied');
                    $this->sseSend('tool_result', array('name' => $tc['name'], 'status' => 'denied', 'result' => $msg));
                }
            }
            $conv['pending'] = array();
            $this->convSave($conv);
            $this->sseSend('conv', array('id' => $conv['id'], 'title' => $conv['title']));
            $this->agentStream($conv, $ctx['cfg'], $ctx['platform'], $ctx['mode'], $ctx['canWrite'], null);
        } catch (\Throwable $e) {
            $this->sseSend('error', array('msg' => $e->getMessage()));
        }
        $this->sseEnd();
    }

    // 工具调用循环（边流式输出边执行工具）
    private function agentStream($conv, $cfg, $platform, $mode, $canWrite, $context)
    {
        $tools = $this->toolDefinitions();
        $maxSteps = isset($cfg['max_steps']) ? max(1, (int)$cfg['max_steps']) : 8;

        for ($step = 0; $step < $maxSteps; $step++) {
            $messages = array_merge(
                array(array('role' => 'system', 'content' => $this->buildSystemPrompt($context))),
                $this->fixMessages($conv['messages'])
            );
            $res = $this->llmStream($messages, $tools, $cfg, $platform);
            if (isset($res['error'])) {
                $this->sseSend('error', array('msg' => $res['error']));
                return;
            }
            $res['content'] = $this->utf8Safe($res['content']);
            $assistant = array('role' => 'assistant', 'content' => $res['content']);
            if (!empty($res['tool_calls'])) {
                $assistant['tool_calls'] = $this->apiToolCalls($res['tool_calls']);
            }
            $conv['messages'][] = $assistant;

            // 没有工具调用 → 本轮对话结束
            if (empty($res['tool_calls'])) {
                $conv['display'][] = array('role' => 'assistant', 'content' => $res['content']);
                $conv['pending'] = array();
                $this->convSave($conv);
                $this->sseSend('done', array('awaiting' => false, 'conversation_id' => $conv['id']));
                return;
            }

            // 判断是否有需要确认的关键操作
            $need = array();
            foreach ($res['tool_calls'] as $tc) {
                if ($this->needsConfirm($tc['name'], $tc['args'])) $need[] = $tc;
            }
            if ($mode === 'confirm' && $canWrite && !empty($need)) {
                $conv['pending'] = $res['tool_calls'];
                if ($res['content'] !== '') $conv['display'][] = array('role' => 'assistant', 'content' => $res['content']);
                $this->convSave($conv);
                $this->sseSend('confirm_required', array(
                    'conversation_id' => $conv['id'],
                    'tools' => $need,
                    'total' => count($res['tool_calls']),
                ));
                $this->sseSend('done', array('awaiting' => true, 'conversation_id' => $conv['id']));
                return;
            }

            if ($res['content'] !== '') $conv['display'][] = array('role' => 'assistant', 'content' => $res['content']);
            $this->execToolCalls($conv, $res['tool_calls'], $canWrite);
            $this->convSave($conv);
        }
        // 达到最大工具步数上限：输出可见提示，避免用户看到空白
        $msg = JZLANG('（已达到单次对话的最大工具调用步数上限，操作已暂停。请继续追问，例如「继续」，或把需求拆成更小的步骤。）');
        $conv['display'][] = array('role' => 'assistant', 'content' => $msg);
        $conv['pending'] = array();
        $this->convSave($conv);
        $this->sseSend('delta', array('text' => $msg));
        $this->sseSend('done', array('awaiting' => false, 'conversation_id' => $conv['id']));
    }

    // 执行一批工具调用
    private function execToolCalls(&$conv, $toolCalls, $canWrite)
    {
        foreach ($toolCalls as $tc) {
            $name = $tc['name'];
            $args = $tc['args'];
            $this->sseSend('tool_run', array('name' => $name, 'args' => empty($args) ? new \stdClass() : $args));
            try {
                $result = $this->runTool($name, $args, $canWrite);
            } catch (\Throwable $e) {
                // 工具内部异常（如 SQL 语法错误）不应中断整条 SSE 流，返回错误让 AI 自行纠正
                $result = JZLANG('工具执行出错：') . $e->getMessage();
            }
            $this->recordRevert($conv, $name, $args, $result);
            $str = $this->toText($result);
            $conv['messages'][] = array('role' => 'tool', 'tool_call_id' => $tc['id'], 'content' => $str);
            $conv['display'][] = array('role' => 'action', 'tool' => $name, 'args' => $args, 'result' => $str, 'status' => 'ok');
            $this->sseSend('tool_result', array('name' => $name, 'status' => 'ok', 'result' => mb_substr($str, 0, 800)));
        }
    }

    // 记录本轮对话产生的「可回滚操作」，供 rollback 还原
    private function recordRevert(&$conv, $name, $args, $result)
    {
        if (empty($conv['turns']) || !is_array($conv['turns'])) return;
        $r = is_array($result) ? $result : array();
        $ops = array();
        if ($name === 'write_file' && !empty($r['ok'])) {
            $rel = isset($r['path']) ? $r['path'] : (isset($args['path']) ? $args['path'] : '');
            if ($rel === '') return;
            if (!empty($r['backup'])) {
                $ops[] = array('type' => 'file_restore', 'rel' => $rel, 'backup' => $r['backup']);
            } else {
                $ops[] = array('type' => 'file_delete', 'rel' => $rel); // 新建文件：回滚即删除
            }
        } elseif ($name === 'delete_file' && !empty($r['ok'])) {
            $rel = isset($r['path']) ? $r['path'] : (isset($args['path']) ? $args['path'] : '');
            if ($rel !== '' && !empty($r['backup'])) {
                $ops[] = array('type' => 'file_restore', 'rel' => $rel, 'backup' => $r['backup']);
            }
        } elseif ($name === 'db_exec' && !empty($r['ok']) && !empty($r['snapshots'])) {
            foreach ($r['snapshots'] as $s) {
                if ($s) $ops[] = array('type' => 'db_restore', 'file' => $s);
            }
        } elseif ($name === 'create_plugin' && !empty($r['ok']) && !empty($r['path'])) {
            $ops[] = array('type' => 'dir_delete', 'dir' => $r['path']);
        }
        if ($ops) {
            $i = count($conv['turns']) - 1;
            if (!isset($conv['turns'][$i]['ops']) || !is_array($conv['turns'][$i]['ops'])) $conv['turns'][$i]['ops'] = array();
            $conv['turns'][$i]['ops'] = array_merge($conv['turns'][$i]['ops'], $ops);
        }
    }

    // 关键操作判定（confirm 模式下需弹窗确认）
    private function needsConfirm($name, $args)
    {
        if (in_array($name, array('write_file', 'delete_file', 'create_plugin'), true)) return true;
        if ($name === 'db_exec') return true;
        return false;
    }

    // 组装发给大模型的 tool_calls
    private function apiToolCalls($tcs)
    {
        $out = array();
        foreach ($tcs as $tc) {
            $args = $tc['args'];
            $argStr = empty($args) ? '{}' : json_encode($args, JSON_UNESCAPED_UNICODE);
            $out[] = array(
                'id' => $tc['id'],
                'type' => 'function',
                'function' => array('name' => $tc['name'], 'arguments' => $argStr),
            );
        }
        return $out;
    }

    // 调用大模型（流式，OpenAI 兼容规范；自动兼容非流式 / 错误响应）
    private function llmStream($messages, $tools, $cfg, $platform)
    {
        $url = rtrim($platform['base_url'], '/') . '/chat/completions';
        $body = array(
            'model' => $platform['model'],
            'messages' => $messages,
            'temperature' => isset($cfg['temperature']) ? (float)$cfg['temperature'] : 0.3,
            'stream' => true,
        );
        if (!empty($cfg['max_tokens'])) $body['max_tokens'] = (int)$cfg['max_tokens'];
        if (!empty($tools)) {
            $body['tools'] = $tools;
            $body['tool_choice'] = 'auto';
        }
        $payload = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $content = '';
        $toolCalls = array();
        $err = null;
        $buffer = '';
        $raw = '';
        $self = $this;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: text/event-stream',
            'Authorization: Bearer ' . $platform['api_key'],
        ));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        $reasoning = '';
        $finish = '';
        $lastThink = 0;
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$buffer, &$raw, &$content, &$toolCalls, &$err, &$reasoning, &$finish, &$lastThink, $self) {
            $raw .= $chunk;
            $buffer .= $chunk;
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);
                $line = preg_replace('/^\xEF\xBB\xBF/', '', $line); // 去掉 BOM
                if ($line === '' || strpos($line, 'data:') !== 0) continue;
                $payloadLine = trim(substr($line, 5));
                if ($payloadLine === '' || $payloadLine === '[DONE]') continue;
                $j = json_decode($payloadLine, true);
                if (!is_array($j)) continue;
                if (isset($j['error'])) {
                    $err = $self->errText($j['error']);
                    continue;
                }
                if (!isset($j['choices'][0])) continue;
                $chosen = $j['choices'][0];
                if (!empty($chosen['finish_reason'])) $finish = $chosen['finish_reason'];
                // 兼容 delta（标准流式）与 message（部分网关）
                $delta = isset($chosen['delta']) ? $chosen['delta'] : (isset($chosen['message']) ? $chosen['message'] : array());
                if (isset($delta['content'])) {
                    $piece = $self->contentToText($delta['content']);
                    if ($piece !== '') {
                        $content .= $piece;
                        $self->sseSend('delta', array('text' => $piece));
                    }
                }
                // 推理模型（deepseek-reasoner 等）：思考内容单独传输，用于保持界面活性
                if (isset($delta['reasoning_content'])) {
                    $rp = $self->contentToText($delta['reasoning_content']);
                    if ($rp !== '') {
                        $reasoning .= $rp;
                        $now = mb_strlen($reasoning);
                        if ($now - $lastThink >= 20) {
                            $lastThink = $now;
                            $self->sseSend('thinking', array('len' => $now));
                        }
                    }
                }
                if (isset($delta['tool_calls']) && is_array($delta['tool_calls'])) {
                    foreach ($delta['tool_calls'] as $tc) {
                        $idx = isset($tc['index']) ? (int)$tc['index'] : 0;
                        if (!isset($toolCalls[$idx])) $toolCalls[$idx] = array('id' => '', 'name' => '', 'arguments' => '');
                        if (isset($tc['id']) && $tc['id']) $toolCalls[$idx]['id'] = $tc['id'];
                        if (isset($tc['function']['name']) && $tc['function']['name']) $toolCalls[$idx]['name'] .= $tc['function']['name'];
                        if (isset($tc['function']['arguments'])) $toolCalls[$idx]['arguments'] .= $tc['function']['arguments'];
                    }
                }
            }
            return strlen($chunk);
        });
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($err !== null) return array('error' => JZLANG('大模型返回错误：') . $err);
        if ($curlErr) return array('error' => JZLANG('请求大模型接口失败：') . $curlErr);

        // 推理模型：思考耗尽输出预算导致正文为空时，兜底使用思考内容，避免判为"未返回内容"
        if ($content === '' && empty($toolCalls) && $reasoning !== '') {
            $content = $reasoning;
            $this->sseSend('delta', array('text' => $content));
        }
        // 输出被 max_tokens 截断时给出可见提示
        if ($finish === 'length' && $content !== '' && empty($toolCalls)) {
            $note = "\n\n" . JZLANG('（提示：输出达到 max_tokens 上限被截断，可到「AI 配置」把 max_tokens 调大，例如 8192）');
            $content .= $note;
            $this->sseSend('delta', array('text' => $note));
        }

        // 1) 标准流式解析成功
        if ($content !== '' || !empty($toolCalls)) {
            return $this->packStreamResult($content, $toolCalls);
        }

        // 2) 回退：接口未按 SSE 返回（或不支持 stream），按整段 JSON 解析
        $json = $this->extractJson($raw);
        if (is_array($json)) {
            if (isset($json['error'])) {
                return array('error' => JZLANG('大模型返回错误：') . $this->errText($json['error']));
            }
            if (isset($json['choices'][0])) {
                $chosen = $json['choices'][0];
                $msg = isset($chosen['message']) ? $chosen['message'] : (isset($chosen['delta']) ? $chosen['delta'] : array());
                $text = isset($msg['content']) ? $this->contentToText($msg['content']) : '';
                $tcs = array();
                if (isset($msg['tool_calls']) && is_array($msg['tool_calls'])) {
                    foreach ($msg['tool_calls'] as $tc) {
                        $args = isset($tc['function']['arguments']) ? $tc['function']['arguments'] : '{}';
                        if (is_string($args)) $args = json_decode($args, true);
                        if (!is_array($args)) $args = array();
                        $tcs[] = array(
                            'id' => isset($tc['id']) ? $tc['id'] : ('call_' . mt_rand(1000, 9999)),
                            'name' => isset($tc['function']['name']) ? $tc['function']['name'] : '',
                            'args' => $args,
                        );
                    }
                }
                if ($text !== '' || !empty($tcs)) {
                    if ($text !== '') $this->sseSend('delta', array('text' => $text));
                    return array('content' => $text, 'tool_calls' => $tcs);
                }
            }
        }

        // 3) 实在解析不出来，带上 HTTP 状态与原始响应片段，便于排查
        if (strpos($raw, 'data:') !== false) {
            return array('error' => JZLANG('平台已返回流式数据，但没有正文内容。常见原因：1) 推理模型（如 deepseek-reasoner）把输出预算都用在思考上；2) max_tokens 设置过小；3) 该模型不支持工具调用。建议到「AI 配置」把 max_tokens 调大（如 8192）或改用非推理模型。原始响应：') . mb_substr($raw, 0, 400));
        }
        return array('error' => JZLANG('接口未返回内容（HTTP ') . $httpCode . JZLANG('）。请确认该平台支持 Chat Completions 接口。原始响应：') . mb_substr($raw, 0, 400));
    }

    // 组装流式解析结果
    private function packStreamResult($content, $toolCalls)
    {
        ksort($toolCalls);
        $tcs = array();
        foreach ($toolCalls as $tc) {
            $args = json_decode($tc['arguments'], true);
            if (!is_array($args)) $args = array();
            $tcs[] = array(
                'id' => $tc['id'] ? $tc['id'] : ('call_' . mt_rand(1000, 9999)),
                'name' => $tc['name'],
                'args' => $args,
            );
        }
        return array('content' => $content, 'tool_calls' => $tcs);
    }

    // 解析整段响应为 JSON（兼容 data: 前缀 / [DONE] / 跨行 JSON）
    private function extractJson($text)
    {
        $text = trim((string)$text);
        if ($text === '') return null;
        if (strpos($text, 'data:') !== false) {
            $text = preg_replace('/^data:\s?/m', '', $text);
            $text = str_replace('[DONE]', '', $text);
            $text = trim($text);
        }
        $j = json_decode($text, true);
        if (is_array($j)) return $j;
        // 多次尝试：截取第一个 { 到最后一个 }
        $s = strpos($text, '{');
        $e = strrpos($text, '}');
        if ($s !== false && $e !== false && $e > $s) {
            $j = json_decode(substr($text, $s, $e - $s + 1), true);
            if (is_array($j)) return $j;
        }
        return null;
    }

    // 提取错误文本
    private function errText($e)
    {
        if (is_array($e)) {
            return isset($e['message']) ? $e['message'] : json_encode($e, JSON_UNESCAPED_UNICODE);
        }
        return (string)$e;
    }

    // 保证字符串为合法 UTF-8（非法字节用 ? 替换），避免 json_encode 失败把 content 变成 false
    private function utf8Safe($s)
    {
        if (!is_string($s)) $s = is_scalar($s) ? (string)$s : '';
        if ($s === '') return '';
        if (function_exists('mb_check_encoding') && !mb_check_encoding($s, 'UTF-8')) {
            $s = @mb_convert_encoding($s, 'UTF-8', 'UTF-8');
        }
        return is_string($s) ? $s : '';
    }

    // 把工具返回结果转成合法 UTF-8 文本（数组则 JSON 序列化；
    // 含非法字节时用 JSON_INVALID_UTF8_SUBSTITUTE 兜底，确保永远不返回 false）
    private function toText($v)
    {
        if (is_string($v)) return $this->utf8Safe($v);
        $flags = JSON_UNESCAPED_UNICODE;
        if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
        $j = @json_encode($v, $flags);
        if (!is_string($j)) $j = @json_encode($v, $flags | 512); // 512 = JSON_PARTIAL_OUTPUT_ON_ERROR（旧版本 PHP 兜底）
        if (!is_string($j)) $j = '';
        return $this->utf8Safe($j);
    }

    // 兼容历史会话：把非法/非字符串的 content 修正为字符串，
    // 避免大模型接口报 "messages.content ... got false"
    private function fixMessages($messages)
    {
        if (!is_array($messages)) return array();
        foreach ($messages as $i => $m) {
            if (!is_array($m)) {
                $messages[$i] = array('role' => 'user', 'content' => $this->toText($m));
                continue;
            }
            $role = isset($m['role']) ? $m['role'] : '';
            if ($role === 'assistant' || $role === 'tool') {
                // assistant / tool 的 content 必须为字符串（assistant 为 null 时置空字符串）
                $m['content'] = (isset($m['content']) && is_string($m['content']))
                    ? $this->utf8Safe($m['content'])
                    : $this->toText(isset($m['content']) ? $m['content'] : '');
            } elseif (isset($m['content']) && is_string($m['content'])) {
                $m['content'] = $this->utf8Safe($m['content']);
            }
            $messages[$i] = $m;
        }
        return $messages;
    }

    // 把 content 统一转成纯文本（兼容字符串 / 多段数组）
    private function contentToText($c)
    {
        if ($c === null) return '';
        if (is_string($c)) return $c;
        if (is_array($c)) {
            $txt = '';
            foreach ($c as $part) {
                if (is_string($part)) {
                    $txt .= $part;
                } elseif (is_array($part)) {
                    if (isset($part['text'])) $txt .= (string)$part['text'];
                    elseif (isset($part['content'])) $txt .= (string)$part['content'];
                }
            }
            return $txt;
        }
        return (string)$c;
    }

    // ------------------------------------------------------------------
    //  工具定义
    // ------------------------------------------------------------------

    private function toolDefinitions()
    {
        // 注意：JSON Schema 里 properties 必须是对象，空参数时要用 stdClass 保证编码为 {}
        $p = function ($type, $desc, $props = null, $required = null) {
            $arr = array('type' => $type, 'description' => $desc);
            if ($props !== null) {
                $arr['properties'] = empty($props) ? new \stdClass() : $props;
                if (!empty($required)) $arr['required'] = $required;
            }
            return $arr;
        };
        return array(
            array('type' => 'function', 'function' => array(
                'name' => 'site_info',
                'description' => '获取站点结构信息：当前前台模板、栏目（classtype）列表、模型（molds）列表、前台模板目录树。仅在【记忆与指引】未说明相关信息位置时调用，不要每次修改都重复调用。',
                'parameters' => $p('object', '无参数', array(), array()),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'list_dir',
                'description' => '列出目录下的文件与子目录（相对站点根目录的路径）。',
                'parameters' => $p('object', '目录参数', array('path' => array('type' => 'string', 'description' => '相对根目录的路径，如 static/cms 或 app/home')), array('path')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'read_file',
                'description' => '读取文本文件内容（相对根目录的路径）。',
                'parameters' => $p('object', '文件参数', array(
                    'path' => array('type' => 'string', 'description' => '相对根目录的文件路径，如 static/cms/index.html'),
                    'max_bytes' => array('type' => 'integer', 'description' => '最多读取字节数，默认 20000'),
                ), array('path')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'search_files',
                'description' => '在指定目录中搜索包含关键词的文件（用于定位某段 HTML 属于哪个模板文件）。',
                'parameters' => $p('object', '搜索参数', array(
                    'keyword' => array('type' => 'string', 'description' => '要搜索的关键词'),
                    'path' => array('type' => 'string', 'description' => '搜索起始目录，默认 static'),
                    'max' => array('type' => 'integer', 'description' => '最多返回文件数，默认 30'),
                ), array('keyword')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'write_file',
                'description' => '写入/覆盖文本文件（相对根目录的路径）。会自动备份原文件。禁止写入系统 PHP 文件。「需要确认」模式下会先弹窗确认。',
                'parameters' => $p('object', '写入参数', array(
                    'path' => array('type' => 'string', 'description' => '相对根目录的文件路径'),
                    'content' => array('type' => 'string', 'description' => '完整文件内容'),
                ), array('path', 'content')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'delete_file',
                'description' => '删除文件（相对根目录的路径）。会自动备份。禁止删除系统 PHP 文件。「需要确认」模式下会先弹窗确认。',
                'parameters' => $p('object', '删除参数', array(
                    'path' => array('type' => 'string', 'description' => '相对根目录的文件路径'),
                ), array('path')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'list_tables',
                'description' => '列出数据库所有数据表。',
                'parameters' => $p('object', '无参数', array(), array()),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'describe_table',
                'description' => '查看数据表字段结构（字段名、类型、注释）。',
                'parameters' => $p('object', '表参数', array(
                    'table' => array('type' => 'string', 'description' => '数据表名，可不带前缀，如 article 或 jz_article'),
                ), array('table')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'db_query',
                'description' => '执行只读查询（仅允许 SELECT / SHOW / DESCRIBE / EXPLAIN）。用于统计数据、查询记录。',
                'parameters' => $p('object', '查询参数', array(
                    'sql' => array('type' => 'string', 'description' => 'SQL 语句，表名需写全（含前缀），如 SELECT * FROM jz_article LIMIT 10'),
                ), array('sql')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'db_exec',
                'description' => '执行写操作（INSERT / UPDATE / DELETE）。执行前会自动快照相关数据表到 backup/ai/db/。「需要确认」模式下会先弹窗确认。',
                'parameters' => $p('object', '写入参数', array(
                    'sql' => array('type' => 'string', 'description' => 'SQL 语句，表名需写全（含前缀）'),
                ), array('sql')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'backup_db',
                'description' => '备份整个数据库到 backup/ 目录。执行重要操作前调用。',
                'parameters' => $p('object', '无参数', array(), array()),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'create_plugin',
                'description' => '创建一个插件骨架到 app/admin/exts/<name>/，包含 config.php、PluginsController.php 与控制器目录。用于覆盖/扩展系统功能。「需要确认」模式下会先弹窗确认。',
                'parameters' => $p('object', '插件参数', array(
                    'name' => array('type' => 'string', 'description' => '插件英文目录名（字母数字下划线），如 myplugin'),
                    'title' => array('type' => 'string', 'description' => '插件中文标题'),
                    'desc' => array('type' => 'string', 'description' => '插件描述'),
                    'module' => array('type' => 'string', 'description' => '应用模块：admin 或 home，默认 admin'),
                ), array('name', 'title')),
            )),
            array('type' => 'function', 'function' => array(
                'name' => 'remember_user',
                'description' => '记录长期有效的信息：用户的稳定偏好/习惯/画像（如称呼、性别、常用风格、技术栈偏好），或「某类信息在何处/如何查找」的指引。当用户明确表达或反复体现时调用，一次记录一条；不要记录临时性信息，也不要重复记录已有内容。',
                'parameters' => $p('object', '记忆参数', array(
                    'content' => array('type' => 'string', 'description' => '一句话描述该特点，如「偏好简洁的代码示例」'),
                ), array('content')),
            )),
        );
    }

    // ------------------------------------------------------------------
    //  工具执行
    // ------------------------------------------------------------------

    private function runTool($name, $args, $canWrite)
    {
        switch ($name) {
            case 'site_info':      return $this->toolSiteInfo();
            case 'list_dir':       return $this->toolListDir($this->arg($args, 'path', '.'));
            case 'read_file':      return $this->toolReadFile($this->arg($args, 'path'), (int)$this->arg($args, 'max_bytes', 20000));
            case 'search_files':   return $this->toolSearchFiles($this->arg($args, 'keyword'), $this->arg($args, 'path', 'static'), (int)$this->arg($args, 'max', 30));
            case 'list_tables':    return $this->toolListTables();
            case 'describe_table': return $this->toolDescribeTable($this->arg($args, 'table'));
            case 'db_query':       return $this->toolDbQuery($this->arg($args, 'sql'));
            case 'write_file':     return $this->toolWriteFile($this->arg($args, 'path'), (string)$this->arg($args, 'content', ''), $canWrite);
            case 'delete_file':    return $this->toolDeleteFile($this->arg($args, 'path'), $canWrite);
            case 'db_exec':        return $this->toolDbExec($this->arg($args, 'sql'), $canWrite);
            case 'backup_db':      return $this->toolBackupDb($canWrite);
            case 'create_plugin':  return $this->toolCreatePlugin($args, $canWrite);
            case 'remember_user':  return $this->toolRememberUser($this->arg($args, 'content'));
            default:               return JZLANG('未知工具：') . $name;
        }
    }

    private function arg($args, $key, $default = '')
    {
        return isset($args[$key]) ? $args[$key] : $default;
    }

    // AI 工具专用数据库连接：出错抛异常，避免框架 DB_API 里的 exit() 中断 SSE 流
    private function aiPdo()
    {
        static $pdo = null;
        if ($pdo === null) {
            $pdo = new \PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
            );
        }
        return $pdo;
    }

    // 执行查询，返回关联数组（失败抛 PDOException）
    private function aiQuery($sql)
    {
        $st = $this->aiPdo()->query($sql);
        return $st ? $st->fetchAll(\PDO::FETCH_ASSOC) : array();
    }

    // 执行写操作，返回影响行数（失败抛 PDOException）
    private function aiExec($sql)
    {
        return $this->aiPdo()->exec($sql);
    }

    // ---- 站点信息 ----
    private function toolSiteInfo()
    {
        $tpl = defined('TEMPLATE') ? TEMPLATE : '';
        $classtype = $this->aiQuery('SELECT id,pid,classname,molds,htmlurl,lists_html,details_html,lists_num,isclose FROM `' . DB_PREFIX . 'classtype` ORDER BY orders desc,id asc');
        $molds = $this->aiQuery('SELECT id,name,biaoshi,list_html,details_html FROM `' . DB_PREFIX . 'molds` ORDER BY id asc');
        $tplDir = 'static/' . ($tpl ? $tpl : '');
        $tree = $this->dirTree($tplDir, 2);
        return array(
            'template' => $tpl,
            'template_dir' => $tplDir,
            'db_prefix' => DB_PREFIX,
            'classtype' => $classtype ? $classtype : array(),
            'molds' => $molds ? $molds : array(),
            'template_tree' => $tree,
            'tip' => '栏目的 lists_html / details_html 即该栏目列表页与详情页使用的模板文件名（相对模板目录 ' . $tplDir . '）；为空时使用所绑定模型的 list_html / details_html 默认值。首页模板为 ' . $tplDir . '/index.html',
        );
    }

    private function dirTree($rel, $depth = 2, $level = 0)
    {
        // 跳过资源目录、限制条数，避免提示词过大
        $skipDirs = array('images', 'image', 'fonts', 'font', 'upload', 'uploads', 'picture', 'node_modules', '.git');
        $out = array();
        $abs = $this->safePath($rel, true);
        if (!$abs || !is_dir($abs)) return $out;
        $items = @scandir($abs);
        if (!$items) return $out;
        $count = 0;
        foreach ($items as $it) {
            if ($it === '.' || $it === '..') continue;
            if ($count >= 80) { $out[] = array('name' => '...(更多省略)', 'type' => 'more'); break; }
            $count++;
            $full = $abs . '/' . $it;
            if (is_dir($full)) {
                if (in_array(strtolower($it), $skipDirs, true)) {
                    $out[] = array('name' => $it, 'type' => 'dir', 'skip' => 1);
                    continue;
                }
                $node = array('name' => $it, 'type' => 'dir');
                if ($level + 1 < $depth) $node['children'] = $this->dirTree($rel . '/' . $it, $depth, $level + 1);
                $out[] = $node;
            } else {
                $out[] = array('name' => $it, 'type' => 'file');
            }
        }
        return $out;
    }

    // ---- 目录 ----
    private function toolListDir($rel)
    {
        $abs = $this->safePath($rel, true);
        if (!$abs || !is_dir($abs)) return JZLANG('目录不存在或路径非法：') . $rel;
        $items = @scandir($abs);
        $out = array();
        foreach ($items as $it) {
            if ($it === '.' || $it === '..') continue;
            $full = $abs . '/' . $it;
            $out[] = array('name' => $it, 'type' => is_dir($full) ? 'dir' : 'file', 'size' => is_dir($full) ? 0 : filesize($full));
        }
        return array('path' => $rel, 'items' => $out);
    }

    // ---- 读取 ----
    private function toolReadFile($rel, $maxBytes = 20000)
    {
        if (!$rel) return JZLANG('缺少文件路径！');
        $abs = $this->safePath($rel, true);
        if (!$abs || !is_file($abs)) return JZLANG('文件不存在或路径非法：') . $rel;
        if ($maxBytes <= 0) $maxBytes = 20000;
        $content = @file_get_contents($abs, false, null, 0, $maxBytes);
        return array('path' => $rel, 'size' => filesize($abs), 'content' => $content === false ? '' : $content);
    }

    // ---- 搜索 ----
    private function toolSearchFiles($keyword, $rel, $max)
    {
        if ($keyword === '' || $keyword === null) return JZLANG('缺少搜索关键词！');
        if ($max <= 0) $max = 30;
        $abs = $this->safePath($rel, true);
        if (!$abs || !is_dir($abs)) return JZLANG('搜索目录不存在：') . $rel;
        $results = array();
        $this->searchRecursive($abs, $keyword, $results, $max);
        return array('keyword' => $keyword, 'count' => count($results), 'files' => $results);
    }

    private function searchRecursive($absDir, $keyword, &$results, $max)
    {
        if (count($results) >= $max) return;
        $items = @scandir($absDir);
        if (!$items) return;
        foreach ($items as $it) {
            if (count($results) >= $max) return;
            if ($it === '.' || $it === '..') continue;
            $full = $absDir . '/' . $it;
            if (is_dir($full)) {
                $this->searchRecursive($full, $keyword, $results, $max);
            } else {
                $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
                if (!in_array($ext, array('html', 'php', 'css', 'js', 'json', 'txt', 'tpl'))) continue;
                if (filesize($full) > 2 * 1024 * 1024) continue;
                $txt = @file_get_contents($full);
                if ($txt !== false && strpos($txt, $keyword) !== false) {
                    $rel = str_replace('\\', '/', substr($full, strlen(str_replace('\\', '/', realpath(APP_PATH))) + 1));
                    $pos = strpos($txt, $keyword);
                    $results[] = array('file' => str_replace('\\', '/', $full), 'rel' => $rel, 'snippet' => mb_substr($txt, max(0, $pos - 80), 240));
                }
            }
        }
    }

    // ---- 写文件 ----
    private function toolWriteFile($rel, $content, $canWrite)
    {
        if (!$canWrite) return JZLANG('无写入权限：写操作仅超级管理员可用。');
        if (!$rel) return JZLANG('缺少文件路径！');
        if ($this->isProtected($rel)) return JZLANG('该文件属于系统 PHP 文件，禁止修改：') . $rel . JZLANG('。如需扩展功能，请在插件目录中实现。');
        $abs = $this->safePath($rel, false);
        if (!$abs) return JZLANG('路径非法：') . $rel;
        $dir = dirname($abs);
        if (!is_dir($dir) && !@mkdir($dir, 0777, true)) return JZLANG('无法创建目录：') . $dir;
        $backup = null;
        if (is_file($abs)) {
            $backup = $this->backupFile($rel, $abs);
        }
        $ok = @file_put_contents($abs, $content);
        if ($ok === false) return JZLANG('写入失败，请检查目录权限：') . $rel;
        $this->logAction('write_file', array('path' => $rel, 'bytes' => strlen($content), 'backup' => $backup));
        return array('ok' => true, 'path' => $rel, 'bytes' => strlen($content), 'backup' => $backup, 'msg' => JZLANG('写入成功'));
    }

    // ---- 删除文件 ----
    private function toolDeleteFile($rel, $canWrite)
    {
        if (!$canWrite) return JZLANG('无写入权限：删除操作仅超级管理员可用。');
        if (!$rel) return JZLANG('缺少文件路径！');
        if ($this->isProtected($rel)) return JZLANG('该文件属于系统文件，禁止删除：') . $rel;
        $abs = $this->safePath($rel, true);
        if (!$abs || !is_file($abs)) return JZLANG('文件不存在：') . $rel;
        $backup = $this->backupFile($rel, $abs);
        if (!@unlink($abs)) return JZLANG('删除失败：') . $rel;
        $this->logAction('delete_file', array('path' => $rel, 'backup' => $backup));
        return array('ok' => true, 'path' => $rel, 'backup' => $backup, 'msg' => JZLANG('已删除，原文件已备份'));
    }

    // ---- 数据表 ----
    private function toolListTables()
    {
        $rows = $this->aiQuery('SHOW TABLES');
        $tables = array();
        foreach ($rows as $r) { foreach ($r as $v) { $tables[] = $v; } }
        return array('tables' => $tables);
    }

    private function toolDescribeTable($table)
    {
        $table = $this->normalizeTable($table);
        if (!$table) return JZLANG('表名非法！');
        $rows = $this->aiQuery('SHOW FULL COLUMNS FROM `' . $table . '`');
        $out = array();
        foreach ($rows as $r) {
            $out[] = array('field' => $r['Field'], 'type' => $r['Type'], 'null' => $r['Null'], 'key' => $r['Key'], 'comment' => isset($r['Comment']) ? $r['Comment'] : '');
        }
        return array('table' => $table, 'columns' => $out);
    }

    private function toolDbQuery($sql)
    {
        $sql = trim((string)$sql);
        if ($sql === '') return JZLANG('缺少 SQL！');
        if (!preg_match('/^\s*(select|show|describe|desc|explain|with)\b/i', $sql)) {
            return JZLANG('db_query 仅允许只读查询（SELECT/SHOW/DESCRIBE/EXPLAIN），写操作请用 db_exec。');
        }
        $this->limitQueryTime();
        $sql = $this->ensureLimit($sql, 500);
        $t0 = microtime(true);
        try {
            $rows = $this->aiQuery($sql);
        } catch (\Throwable $e) {
            return JZLANG('SQL 执行失败：') . $e->getMessage();
        }
        $cost = (int)round((microtime(true) - $t0) * 1000);
        $rows = is_array($rows) ? array_slice($rows, 0, 200) : array();
        return array('rows' => $rows, 'count' => count($rows), 'cost_ms' => $cost, 'executed_sql' => $sql, 'note' => JZLANG('最多返回 200 行'));
    }

    // 限制单条 SQL 的执行时间，避免慢查询把整个请求卡住
    private function limitQueryTime()
    {
        static $done = false;
        if ($done) return;
        $done = true;
        foreach (array('SET SESSION max_execution_time=15000', 'SET SESSION max_statement_time=15') as $s) {
            try { $this->aiExec($s); } catch (\Throwable $e) {}
        }
    }

    // 给未带 LIMIT 的 SELECT 自动补 LIMIT，避免一次拉取全表
    private function ensureLimit($sql, $n = 500)
    {
        if (!preg_match('/^\s*(select|with)\b/i', $sql)) return $sql;
        if (preg_match('/\blimit\s+\d/i', $sql)) return $sql;
        return rtrim(rtrim($sql, ';'), " \t\n\r") . ' LIMIT ' . (int)$n;
    }

    private function toolDbExec($sql, $canWrite)
    {
        if (!$canWrite) return JZLANG('无写入权限：数据写操作仅超级管理员可用。');
        $sql = trim((string)$sql);
        if ($sql === '') return JZLANG('缺少 SQL！');
        if (preg_match('/^\s*(select|show|describe|desc|explain)\b/i', $sql)) {
            return JZLANG('该语句为只读查询，请使用 db_query。');
        }
        // 安全：UPDATE / DELETE 必须带 WHERE，避免全表锁与误删
        if (preg_match('/^\s*(update|delete)\b/i', $sql) && !preg_match('/\bwhere\b/i', $sql)) {
            return JZLANG('为避免误操作，UPDATE / DELETE 必须带 WHERE 条件（如确需操作全表请写 WHERE 1=1）。');
        }
        $this->limitQueryTime();
        // 快照相关表
        $snapshots = array();
        $tables = $this->extractTables($sql);
        foreach ($tables as $t) {
            $t = $this->normalizeTable($t);
            if ($t) $snapshots[] = $this->snapshotTable($t);
        }
        $r = null;
        try {
            $r = $this->aiExec($sql);
        } catch (\Throwable $e) {
            return JZLANG('SQL 执行失败：') . $e->getMessage();
        }
        if ($r === false) return JZLANG('SQL 执行失败，请检查语句与表名。');
        $this->logAction('db_exec', array('sql' => $sql, 'affected' => $r, 'snapshots' => $snapshots));
        return array('ok' => true, 'affected' => $r, 'snapshots' => $snapshots, 'msg' => JZLANG('执行成功'));
    }

    private function toolBackupDb($canWrite)
    {
        if (!$canWrite) return JZLANG('无权限：仅超级管理员可执行备份。');
        $pconfig = array(
            'host' => DB_HOST, 'port' => DB_PORT, 'user' => DB_USER,
            'password' => DB_PASS, 'database' => DB_NAME, 'prefix' => DB_PREFIX,
        );
        $database = new \DatabaseTool($pconfig);
        $database->backup();
        $this->logAction('backup_db', array());
        return array('ok' => true, 'msg' => JZLANG('数据库已备份到 backup/ 目录'));
    }

    // ---- 插件脚手架 ----
    private function toolCreatePlugin($args, $canWrite)
    {
        if (!$canWrite) return JZLANG('无写入权限：创建插件仅超级管理员可用。');
        $name = isset($args['name']) ? trim($args['name']) : '';
        if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $name)) return JZLANG('插件名只能为字母开头的字母数字下划线组合！');
        $title = isset($args['title']) ? trim($args['title']) : $name;
        $desc = isset($args['desc']) ? trim($args['desc']) : '';
        $module = (isset($args['module']) && strtolower($args['module']) == 'home') ? 'home' : 'admin';
        $uc = ucfirst($name);

        $base = 'app/admin/exts/' . $name;
        $abs = $this->safePath($base, false);
        if (!$abs) return JZLANG('插件路径非法！');
        if (is_dir($abs)) return JZLANG('插件已存在：') . $base;

        @mkdir($abs . '/controller/' . $module, 0777, true);
        @mkdir($abs . '/tpl', 0777, true);

        $configTpl = "<?php\nreturn [\n\t'name'=>'{$title}',\n\t'desc'=>'{$desc}',\n\t'author'=>'AI 助手',\n\t'version'=>'1.0',\n\t'update_time'=>'" . date('Y-m-d') . "',\n\t'module'=>'" . ($module == 'home' ? 'Home' : 'Admin') . "',\n];\n";
        file_put_contents($abs . '/config.php', $configTpl);

        $pluginsCtrl = "<?php\nnamespace app\\admin\\exts;\n\nclass PluginsController\n{\n\tpublic function install(){\n\t\t// TODO: 建表、复制文件、注册 hook\n\t\tsetCache('hook',null);\n\t\treturn true;\n\t}\n\tpublic function uninstall(){\n\t\tsetCache('hook',null);\n\t\treturn true;\n\t}\n\tpublic function desc(){\n\t\techo '{$desc}';\n\t}\n}\n";
        file_put_contents($abs . '/PluginsController.php', $pluginsCtrl);

        $ctrlNs = $module == 'home' ? 'app\\home\\plugins' : 'app\\admin\\plugins';
        $ctrlTpl = "<?php\nnamespace {$ctrlNs};\n\nuse app\\" . $module . "\\c\\CommonController;\n\nclass {$uc}Controller extends CommonController\n{\n\tfunction index(){\n\t\techo '插件 {$title} 运行中';\n\t}\n}\n";
        file_put_contents($abs . '/controller/' . $module . '/' . $uc . 'Controller.php', $ctrlTpl);

        $this->logAction('create_plugin', array('name' => $name, 'module' => $module));
        return array('ok' => true, 'path' => $base, 'msg' => JZLANG('插件骨架已创建，可继续用 write_file 补充文件，然后到「插件列表」安装。'));
    }

    // ------------------------------------------------------------------
    //  工具辅助
    // ------------------------------------------------------------------

    private function normalizeTable($table)
    {
        $table = trim((string)$table);
        if ($table === '') return false;
        if (!preg_match('/^[A-Za-z0-9_]+$/', $table)) return false;
        if (strpos($table, DB_PREFIX) !== 0) {
            $table = DB_PREFIX . $table;
        }
        return $table;
    }

    private function extractTables($sql)
    {
        $tables = array();
        if (preg_match_all('/\b(?:from|into|update|join|table)\s+`?([A-Za-z0-9_]+)`?/i', $sql, $m)) {
            $tables = array_unique($m[1]);
        }
        return $tables;
    }

    private function snapshotTable($table)
    {
        $dir = APP_PATH . 'backup/ai/db';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $file = $dir . '/' . $table . '_' . date('YmdHis') . '_' . mt_rand(100, 999) . '.sql';

        $create = $this->aiQuery('SHOW CREATE TABLE `' . $table . '`');
        if (!$create) return null;
        $ddl = '';
        foreach ($create[0] as $k => $v) { if (stripos($k, 'create') !== false) { $ddl = $v; break; } }
        $sql = "-- AI 快照: {$table} " . date('Y-m-d H:i:s') . "\n";
        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n" . $ddl . ";\n";
        $rows = $this->aiQuery('SELECT * FROM `' . $table . '`');
        if ($rows) {
            foreach ($rows as $row) {
                $cols = array(); $vals = array();
                foreach ($row as $k => $v) {
                    $cols[] = '`' . $k . '`';
                    $vals[] = $v === null ? 'NULL' : "'" . addslashes($v) . "'";
                }
                $sql .= "INSERT INTO `{$table}` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ");\n";
            }
        }
        @file_put_contents($file, $sql);
        return str_replace(str_replace('\\', '/', realpath(APP_PATH)) . '/', '', str_replace('\\', '/', $file));
    }

    private function backupFile($rel, $abs)
    {
        $dir = APP_PATH . 'backup/ai/file/' . date('YmdHis');
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $target = $dir . '/' . str_replace('/', '_', $rel);
        if (@copy($abs, $target)) {
            return str_replace(str_replace('\\', '/', realpath(APP_PATH)) . '/', '', str_replace('\\', '/', $target));
        }
        return null;
    }

    private function isProtected($rel)
    {
        $rel = strtolower(ltrim(str_replace('\\', '/', (string)$rel), '/'));
        foreach ($this->protectDirs as $d) {
            if (strpos($rel, $d) === 0) return true;
        }
        if (in_array($rel, $this->protectFiles)) return true;
        return false;
    }

    private function safePath($rel, $mustExist = false)
    {
        $rel = trim(str_replace('\\', '/', (string)$rel));
        $rel = ltrim($rel, '/');
        if ($rel === '' || strpos($rel, '..') !== false || preg_match('#^[A-Za-z]:#', $rel)) return false;
        $root = str_replace('\\', '/', realpath(APP_PATH));
        if (!$root) return false;
        $full = $root . '/' . $rel;
        $full = preg_replace('#/\./#', '/', $full);
        $checkDir = $mustExist ? $full : dirname($full);
        while (strlen($checkDir) > 2 && !file_exists($checkDir)) {
            $checkDir = dirname($checkDir);
        }
        $real = realpath($checkDir);
        if ($real === false) return false;
        $real = str_replace('\\', '/', $real);
        if ($real !== $root && strpos($real . '/', $root . '/') !== 0) return false;
        return $full;
    }

    private function logAction($type, $data)
    {
        $dir = APP_PATH . 'backup/ai/log';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $line = date('Y-m-d H:i:s') . "\t" . $type . "\t" . (isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : '') . "\t" . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        @file_put_contents($dir . '/' . date('Ym') . '.log', $line, FILE_APPEND);
    }

    // ------------------------------------------------------------------
    //  请求解析 & 系统提示词
    // ------------------------------------------------------------------

    private function jsonInput()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : array();
    }

    private function buildSystemPrompt($context = null)
    {
        $tpl = defined('TEMPLATE') ? TEMPLATE : '';
        $map = "站点根目录结构：\n" .
            "app/admin/c/ 后台系统控制器(禁止修改)；app/admin/t/ 后台模板(禁止修改)；app/admin/plugins/ 后台插件覆盖控制器(可写)；app/admin/exts/ 插件安装包(可写)\n" .
            "app/home/c/ 前台系统控制器(禁止修改)；app/home/plugins/ 前台插件覆盖控制器(可写)；app/home/template/ 旧版前台模板\n" .
            "frphp/ 框架内核(禁止修改)；conf/FunctionsExt.php 与 conf/route.php 可写；conf/config.php 禁止修改\n" .
            "static/ 网站前台模板与静态资源(可写，当前模板目录 static/{$tpl})；static/upload/ 上传目录；backup/ 备份目录\n";

        $prompt = "你是「极致CMS（JiZhiCMS / FrPHP 框架）」后台的 AI 开发助手，能用中文帮站长完成二次开发与数据管理。\n\n";
        $prompt .= "【安全边界】\n";
        $prompt .= "1. 可写：static/、app/home/plugins/、app/admin/plugins/、app/admin/exts/、conf/FunctionsExt.php、conf/route.php、backup/。\n";
        $prompt .= "2. 禁写：frphp/、app/admin/c/、app/admin/t/、app/home/c/、install/、index.php、conf/config.php 等系统 PHP 文件；这些只能读取。\n";
        $prompt .= "3. 需要改系统功能时，不要改系统文件，而应创建插件（app/admin/exts/<name>/，或直接在 app/admin/plugins/、app/home/plugins/ 下写同名控制器覆盖系统控制器）。CommonController 覆盖方式：plugins 目录下创建同名 CommonController 即可覆盖系统公共控制器。\n";
        $prompt .= "4. 任何写文件、删文件、写数据库之前都要先读取确认；写操作会自动备份。\n\n";
        $prompt .= "【数据库规则】\n";
        $prompt .= "表前缀：" . DB_PREFIX . "。只读查询用 db_query（仅 SELECT/SHOW/DESCRIBE/EXPLAIN）；写操作用 db_exec（INSERT/UPDATE/DELETE），执行前系统会自动快照相关表。重要操作前先调用 backup_db。\n";
        $prompt .= "查询务必带上 LIMIT（如 LIMIT 20），统计用 COUNT(*)，不要 SELECT 全表；UPDATE/DELETE 必须带 WHERE 条件。\n\n";
        $prompt .= "【关键结构】\n" . $map;
        $prompt .= "- 栏目表 classtype：字段 id、pid、classname（栏目名）、molds（模型）、htmlurl（栏目目录）、lists_num（每页数量）、isclose。\n";
        $prompt .= "- 模型 molds：如 article(文章)、product(商品)、message(留言)，其它见 molds 表。\n";
        $prompt .= "- 前台模板：当前模板目录为 static/{$tpl}；首页为 static/{$tpl}/index.html。栏目列表页/详情页模板由 classtype.lists_html 与 classtype.details_html 指定（相对模板目录），为空时取模型 molds.list_html / molds.details_html 默认值。用 site_info 工具可获取全部栏目与其模板文件。\n";
        $prompt .= "- 模板标签：{loop}、{if}、{foreach}、{include=\"xx\"}、{fun 函数()}、{\$变量}；栏目变量 \$classtypedata。\n";
        $prompt .= "- 后台菜单/权限表 ruler（name、fc=控制器/方法、pid、isdesktop、sys）；桌面布局表 layout。\n";
        $prompt .= "- 系统配置表 sysconfig（field、data）。\n\n";
        $prompt .= "【工作方式】\n";
        $prompt .= "先探索（site_info / list_dir / read_file / search_files）再动手；不确定时先询问用户。修改完成后用简体中文简要说明：改了哪些文件、做了什么、如何验证。\n";
        $prompt .= "如果【记忆与指引】里已说明某类信息在何处、用什么工具查，就直接照做，不要反复调用 site_info / list_dir / search_files 做重复探索。\n";
        $prompt .= "当你在对话中了解到用户稳定的偏好、习惯或画像（如称呼、性别、常用风格、技术栈偏好），或总结出「某类信息在何处/如何查找」的指引时，调用 remember_user 记录；同一信息不要重复记录。\n";

        // 记忆与指引（用户可在「AI 配置」页维护）
        $mem = $this->memoryText();
        if ($mem !== '') $prompt .= "\n" . $mem . "\n";

        // 可视化选中上下文
        if (!empty($context) && is_array($context)) {
            $c = $context;
            $prompt .= "\n【用户当前选中的页面元素】\n";
            if (!empty($c['url'])) $prompt .= "页面地址：" . $c['url'] . "\n";
            if (!empty($c['selector'])) $prompt .= "CSS 选择器：" . $c['selector'] . "\n";
            if (!empty($c['html'])) $prompt .= "元素 HTML（截断）：" . mb_substr($c['html'], 0, 2000) . "\n";
            $prompt .= "用户就是要修改这段被选中的元素。请先用 search_files（关键词取该 HTML 中一段独特的文字或 class）定位其所属模板文件，read_file 确认后再用 write_file 修改该模板文件；不要盲目浏览无关目录，尽量在 2-3 步内完成。\n";
        }
        return $prompt;
    }
}
