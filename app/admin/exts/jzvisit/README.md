# 访问统计插件（jzvisit）

极致CMS / FrPHP 插件：统计前台**浏览量**与**浏览记录**，后台可视化管理，可打包转移到其他极致CMS站点。

## 一、目录结构（安装包）

```
app/admin/exts/jzvisit/
├── config.php                 插件信息（名称/版本/作者/模块）
├── PluginsController.php      安装、卸载、配置（建表 + 注册钩子 + 注册后台菜单）
├── controller/
│   ├── home/VisitController.php    前台采集器（安装后复制到 app/home/plugins/）
│   └── admin/VisitController.php   后台管理   （安装后复制到 app/admin/plugins/）
└── tpl/
    ├── plugins-description.html    插件列表“安装说明”
    ├── plugins-body.html           插件列表“配置”（开关、防刷、蜘蛛、清理天数…）
    ├── visit-index.html            统计概览
    ├── visit-lists.html            浏览记录
    ├── visit-content.html          内容排行
    └── visit-clear.html            清理记录
```

## 二、安装

1. 后台 → 插件管理 → 插件列表：找到「访问统计」（插件文件 jzvisit）→ 点 **安装**；
2. 安装程序会自动：
   - 创建数据表 `jz_visit_log`（浏览明细）、`jz_visit_day`（按天汇总）；
   - 向 `jz_hook` 注册钩子：模块 `home`、控制器 `Home`、全部方法 → 调用 `Visit/record`；
   - 新增后台权限与菜单「访问统计」，并挂到桌面左侧菜单；
   - 把控制器复制到 `app/home/plugins/` 与 `app/admin/plugins/`；
3. 安装后无需修改任何模板即可开始统计。想在“插件列表”里关闭统计，用配置里的「开启前台统计」开关即可。

## 三、功能

| 页面 | 说明 |
| --- | --- |
| 统计概览 | 今日/昨日 PV、独立访客 UV、累计 PV/UV、今日蜘蛛数、近 N 天趋势、栏目排行 |
| 浏览记录 | 时间、页面标题、栏目、IP、设备/系统/浏览器、来源 Referer、访问地址；支持栏目/类型/IP/关键词/日期筛选 |
| 内容排行 | 按内容统计 PV、UV、最近访问时间，可直接点标题跳前台（含子栏目汇总） |
| 清理记录 | 按保留天数删除历史明细，或超级管理员一键清空 |

## 四、配置项

- **开启前台统计**：总开关；
- **记录蜘蛛访问**：蜘蛛不计入 PV/UV，只单独统计；
- **不统计管理员**：登录后台时浏览前台不记录；
- **只记录详情页**：只统计内容详情页；
- **同步内容浏览量**：把访问次数写入内容表 `hits`。默认关闭（当前模板详情页已用 `/common/gohits` 计数，开启会重复计数）；
- **同IP同页间隔**：防刷新灌水，默认 60 秒；
- **明细保留天数**：清理页面默认值；
- **不统计的IP**：逗号分隔。

## 五、前台接口

```
/visit/num/molds/article/id/12.html     返回该内容浏览量（纯数字）
/visit/num/molds/article/id/12/ajax/1   JSON：{"code":0,"data":123}
/visit/num/molds/article/id/12?ajax=1   JSON
```

## 六、转移到其他网站

在「插件管理 → 插件列表」点击本插件的 **导出**，得到 `jzvisit.zip`；上传到目标站
`app/admin/exts/` 解压后，在插件列表点 **安装** 即可（自动建表、注册钩子与菜单）。
注意：目标站表前缀不同也没关系，安装程序统一使用 `DB_PREFIX`。

## 七、卸载

插件列表 → **卸载**：移除钩子、后台权限菜单与桌面菜单项；数据表保留（重装后数据还在）。
需要彻底删除数据时，先在“清理记录”里清空，再手动删除 `jz_visit_log`、`jz_visit_day` 两张表。
