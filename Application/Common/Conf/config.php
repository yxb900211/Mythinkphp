<?php
return array(
	'DB_TYPE'      => 'mysql',//数据库类型
	'DB_HOST'      => 'localhost',//服务器地址
	'DB_NAME'      => 'yin_demo',//数据库名
	'DB_USER'      => 'root',//用户名
	'DB_PWD'       => 'root',//密码
	'DB_PORT'      => 3306,//端口
	'DB_PREFIX'    => 'yin_',//数据库表前缀
	'DB_CHARSET'   => 'utf8',//字符集
	// 'TMPL_L_DELIM' =>'{',
	// 'TMPL_R_DELIM' =>'}',
	//默认错误跳转对应的模板文件
	//'TMPL_ACTION_ERROR'   => 'Public:error',
	//默认成功跳转对应的模板文件
	//'TMPL_ACTION_SUCCESS' => 'Public:success',
	/*|--------------------------以下为框架配置--------------------------|*/
	/**
	 * PAGE_NUMBER
	 * 每页分页显示的数据条数设置
	 */
	'PAGE_NUMBER'  => 1,
	/**
	 * PAGE_CONFIG
	 * 分页config配置位置。
	 */
	'PAGE_CONFIG'  => array(
            'prev'   => '<<',
            'next'   => '>>',
            'last'   => '尾页',
            'first'  => '首页',
            'theme'  => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
            ),
	/**
	 * PAGE_CLASS
	 * 分页样式配置位置。
	 */
	// 'PAGE_CLASS'   => array(
 //            'header'     => 'class="a"',//共##条数据的class配置
 //            'upRow'      => 'class="a"',//上一页
 //            'linknum'    => 'class="a"',//1 2 3 4 5 6
 //            'nowpage'    => 'class="a active"',//当前页
 //            'first'      => 'class="a"',//首页
 //            'downRow'    => 'class="a"',//下一页
 //            'last'       => 'class="a"',//尾页
 //            'no_upRow'   => '<a class="a previous-off"><<</a>',//当没有上一页的时候显示什么，默认空
 //            'no_downRow' => '<a class="a previous-off">>></a>',//当没有下一页的时候显示什么，默认空
 //            ),
	/**
	 * UPLOAD_CONFIG
	 * 上传类配置位置
	 */
	// 'UPLOAD_CONFIG' => array(
	// 	        'maxSize'  => 5242880,
 //                'savePath' => './Uploads/',
 //                'saveName' => array('uniqid',''),
 //                'exts'     => array('jpg', 'gif', 'png', 'jpeg'),
 //                'autoSub'  => true,
 //                'subName'  => array('date','Ymd'),
	// 	),
	/**
	 * FILE_REALNAME
	 * 配置上传文件是否保存真实文件名称，以及保存的字段名称。可批量配置。
	 * 支持格式 varchar 或者 array格式配置
	 * 批量相同配置可使用 varchar 格式，
	 * 根据字段名实行不同配置可使用array 格式，
	 * 如果数组内包含键值‘ALL’则为默认设置，
	 * 值以‘_’开头为后缀模式，会根据上传文件字段名称增加后缀
	 * 值以非‘_’开头为完整字段名称设置。
	 */
	// 'FILE_REALNAME' => array(
	//         'file' => '_name',
	//         'ALL'  => '_name',//设置默认方式
	//         ),
	/**
	 * OPEN_STATUS
	 * 是否开启自动加载逻辑删除模式。默认开启，false为关闭。
	 */
	// 'OPEN_STATUS' => true,
	/**
	 * DEL_STATUS
	 * 配置逻辑删除的字段详情，
	 * 支持格式 varchar 或者 array格式配置
	 * 批量相同配置可使用 varchar 格式 ::'字段名,删除的值'
	 * 根据字段名实行不同配置可使用array 格式，:: '模型名称' => '字段名,删除的值'
	 * 如果数组内包含键值‘ALL’则为默认设置，
	 */
	// 'DEL_STATUS'  => array(
	//         'ALL'  => 'status,none',//设置默认方式
	//         ),
	//
	//数据库备份还原配置
	'DB_PATH_NAME'=> 'db',        //备份目录名称,主要是为了创建备份目录
	'DB_PATH'     => './db/',     //数据库备份路径必须以 / 结尾；
	'DB_PART'     => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
	'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
	'DB_LEVEL'    => '9',         //压缩级别   1:普通   4:一般   9:最高

	/*邮箱配置*/
	'MAIL_HOST' =>'smtp.163.com',// smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'*******@163.com',//你的邮箱名
    'MAIL_FROM' =>'*******@163.com',//发件人地址
    'MAIL_FROMNAME'=>'蜂蝶旋舞',//发件人姓名
    'MAIL_PASSWORD' =>'******',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);