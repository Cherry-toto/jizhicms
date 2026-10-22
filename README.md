[![极致CMS建站系统](https://www.jizhicms.cn/header_1920x700.png)](http://down.jizhicms.cn/jizhicms.zip)

<h1 align="center">极致CMS建站系统</h1>

<p align="center">一款免费开源的PHP建站CMS系统，可以免授权商业使用该系统。</p>

<p align="center">
<img src="https://img.shields.io/badge/jizhicms-v3.0-important.svg" title="jizhicms" />
<img src="https://img.shields.io/badge/PHP-8.0+-blue.svg" title="PHP" />
<img src="https://img.shields.io/badge/MySQL-4.8+-red.svg" title="MySQL" />
</p>
<p align="center">
<img src="https://img.shields.io/badge/Apache-green.svg" title="Apache" />
<img src="https://img.shields.io/badge/Linux-success.svg" title="Linux" />
<img src="https://img.shields.io/badge/MIT-brightgreen.svg" title="MIT" />
</p>
<p align="center">
	<img src="https://gitee.com/Cherry_toto/jizhicms/badge/star.svg?theme=dark" alt="Gitee stars"/>  
	<img src="https://img.shields.io/github/stars/Cherry-toto/jizhicms" alt="GitHub stars"/>
</p>

官网：[www.jizhicms.cn](https://www.jizhicms.cn) | [www.jizhicms.com](https://www.jizhicms.com) | [应用市场](https://app.jizhicms.cn)   
演示：[demo.jizhicms.cn](https://demo.jizhicms.cn)   
后台：[https://demo.jizhicms.cn/admin.php](https://demo.jizhicms.cn/admin.php) [ 账户：test 密码：123456 ]  
单入口后台：[https://demo.jizhicms.cn/index.php/admins](https://demo.jizhicms.cn/index.php/admins) [ 账户：test 密码：123456 ]  
文档：[https://help.jizhicms.cn](https://help.jizhicms.cn)   
工具：[https://h.jizhicms.cn](https://h.jizhicms.cn)   
作者：留恋风（QQ：2581047041 微信：TF-2581047041）  
商务合作：13660240863    
QQ交流群：774764507 , 869094463 , 651403450 [【加群】](https://jq.qq.com/?_wv=1027&k=6zjN1tgq) 

## 极致CMS 3.0 重磅更新：内置 AI 助手，让建站进入 AI 时代！
> 用一句话就能改模板、管数据、写插件，AI 全程自动执行  
> 兼容 OpenAI 接口规范，支持 OpenAI / DeepSeek / 通义千问 / 智谱 / Moonshot 等主流大模型，后台自由切换  
> 流式输出（SSE）+ Function Calling 工具调用，AI 边思考边动手，进度实时可见  
> **会改模板**：自动定位前台模板、栏目页面和静态资源，按你的描述直接修改代码  
> **会管数据**：查询表结构、增删查改内容、导出统计数据，还能一键备份数据库  
> **会写插件**：一句话生成插件，直接扩展系统功能，无需手动搭建目录和配置  
> **可视化编辑**：在页面预览中点选任意区域，描述修改要求，AI 自动改代码，支持 PC / 手机视图  
> **安全可控**：「需要确认」模式逐项确认，「完全控制」模式直接执行，所有写操作自动备份  
> **支持回滚**：多会话、历史记录、跨设备查看，一键撤销 AI 对文件和数据库的改动  
> **长期记忆**：可维护用户画像与站点指引，AI 越用越懂你的网站  
> AI 助手入口：后台顶部导航「AI 助手」，首次使用请到「AI 配置」添加大模型平台并填写 API Key，可一键初始化左侧菜单与权限  

### 3.0 其他更新
* 新增访问统计插件，支持访问数据统计与查看  
* 新增图库功能，编辑器支持从图库选择图片  
* 新增缓存管理功能，优化前后台 session 处理  
* 数据库操作改用预处理语句，增强 SQL 注入防护  
* 上传安全加固，禁止上传 phar 等危险文件类型  
* 系统要求 PHP 8.0 及以上，兼容 PHP 8.2 / 8.4  

## 极致CMS建站系统2.0 重磅更新！
> 支持后台在线模板，可以下载和更新模板，提供更多免费模板  
> 支持后台多语言本地化配置  
> 更新一套精美模板免费使用  
> 桌面配置更具人性化设定，支持更多图标选择及菜单名称自定义  
> 更具通俗化目录结构，让你一目了然  
> 字段重构，前台发布表单字段设定，列表字段控制，多种类型字段让你自由发挥创作！  
> 后台增加模板自定义方式，让你二开更方便！  
> 后台插件模板接口开放设定，可以自由维护自己的客户进行客制化服务！  
> 免费开源，代码无任何加密，可免费商用，前台界面可以去除版权显示，支持MIT协议，真正开源免费！  

   

*****
## 安装环境
* jizhicms的开发语言是PHP，要求PHP8.0及以上版本，<b>不再支持PHP8.0以下版本</b>，并可直接运行在最新PHP版本（如PHP8.4）上。
* 安装环境建议Linux服务器，当然Windows也是可以安装的，云服务器建议安装宝塔面板 [查看](http://www.bt.cn/)
* 无论是Windows还是Linux建议安装Apache、mysql、phpMyAdmin配件 
* 本系统当前版本2.0仅支持mysql数据库

## 安装须知  
* PHP8.0+（不支持PHP8.0以下版本，兼容最新PHP8.x版本）   
* MySQL4.8+，数据编码 <b>utf-8</b> 或者 <b>utf8-general-ci</b>   
* 部署环境必须支持伪静态  
* 支持Apache、Nginx、IIS     

## 开始安装  
1. 下载源码并解压源码到空间根目录(www)      
2. 浏览器访问 http://127.0.0.1/install/ (本地环境) http://绑定域名/install/ (空间服务器环境)   
3. 进入安装步骤，逐步进行安装   
4. 初次安装请安装备份或者测试数据    
5. 安装完毕后，为了安全，请删除根目录intall安装文件夹    

## 网站搬家   
1. 进入后台--【系统扩展】--【数据库列表】，点击 <b>备份数据库</b> 将当前数据库备份    
2. 将cache文件夹下面的缓存文件全部删除，并在根目录放置官方源码里的install文件夹        
3. 将整个根目录打包下载到本地    
4. 将打包后的压缩包上传到新空间   
5. 按照【开始安装】的步骤进行安装程序     
6. 安装第二步填入数据库账号密码后，选择你最新备份的数据库，点击进行安装    
7. 安装完毕，为了安全，请删除根目录intall安装文件夹   

## 版权声明
![极致CMS 2.0版权证明](https://www.jizhicms.cn/copyright.png)

## 支持协议  
[MIT](https://gitee.com/Cherry_toto/jizhicms/blob/2.0/LICENSE)

![极致CMS建站系统](https://www.jizhicms.cn/footer_1920x273.png)

