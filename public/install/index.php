<?php
error_reporting(0);
@header('Content-Type: text/html; charset=UTF-8');
include_once '../../env.php';
$step = isset($_GET['step']) ? $_GET['step'] : '0';
if (file_exists('su.lock')) {
  $installed = true;
  $step = '0';
}
$web_url = dirname((($_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], (substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') ? '/' : '', dirname($_SERVER['SCRIPT_FILENAME']))); //当前域名
?>
<!DOCTYPE html>
<html lang="zh">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="viewport" content="width=device-width, initial-scale=1.0" />
  <title>博客安装向导</title>
  <link href="../install/css/bootstrap.min.css" rel="stylesheet">
  <link href="../install/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="../install/css/style.min.css" rel="stylesheet">
</head>

<body><br>
  <div class="lyear-layout-web">
    <div class="lyear-layout-container">
      <div class="container-fluid">

        <div class="row">
          <?php
          if ($step == 0) {
          ?>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header text-center">
                  <h4>程序安装引导</h4>
                </div>
                <div class="card-body">
                  <p><iframe src="../readme.html" style="width:100%;height:50%;"></iframe></p>
                  <?php if ($installed) { ?>
                    <div class="alert alert-warning">您已经安装过，如需覆盖原有的数据重新安装请删除<font color=red> su.lock </font>文件后再安装！</div>
                    <div class="alert alert-warning">
                      <font color=red> 覆盖性安装会将原有的数据全部覆盖掉清空，将永久无法恢复这些数据请慎重操作~~ </font>
                    </div>
                  <?php } else { ?>
                    <p align="center"><a class="btn btn-primary" href="/install/index.php?step=1">开始安装</a></p>
                  <?php } ?>
                </div>
              </div>
            </div>
          <?php
          } elseif ($step == 1) {
          ?>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header text-center">
                  <h4>数据库信息配置</h4>
                </div>
                <div class="card-body">
                  <form method="post" action="?step=2">

                    <div class="form-group">
                      <label for="localhost">数据库地址</label>
                      <input class="form-control" type="text" name="host" value="localhost">
                    </div>

                    <div class="form-group">
                      <label for="user">数据库账号</label>
                      <input class="form-control" type="text" name="user" value="">
                    </div>

                    <div class="form-group">
                      <label for="dbname">数据库名</label>
                      <input class="form-control" type="text" name="dbname" value="">
                    </div>

                    <div class="form-group">
                      <label for="pass">数据库密码</label>
                      <input class="form-control" type="text" name="pass" value="">
                    </div>

                    <div class="form-group">
                      <label for="port">端口</label>
                      <input class="form-control" type="text" name="port" value="3306">
                    </div>
                    <p align="center"><button type="submit" class="btn btn-primary">下一步</button></p>
                  </form>
                </div>
              </div>
            </div>
          <?php
          } elseif ($step == 2) {
          ?>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header text-center">
                  <h4>检查数据库连接</h4>
                </div>
                <div class="card-body">
                  <?php
                  $host = isset($_POST['host']) ? $_POST['host'] : NULL;
                  $user = isset($_POST['user']) ? $_POST['user'] : NULL;
                  $dbname = isset($_POST['dbname']) ? $_POST['dbname'] : NULL;
                  $pass = isset($_POST['pass']) ? $_POST['pass'] : NULL;
                  $port = isset($_POST['port']) ? $_POST['port'] : NULL;
                  if ($host == null || $port == null || $user == null || $pass == null || $dbname == null) {
                    echo '<div class="alert alert-danger">保存错误,请确保每项都不为空<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a></div>';
                    exit();
                  }
                  //测试链接状态
                  $con = mysqli_connect($host, $user, $pass, $dbname, $port);
                  $config = "APP_DEBUG = false

[APP]
DEFAULT_TIMEZONE = Asia/Shanghai
                  
[DATABASE]
TYPE = mysql
HOSTNAME = 127.0.0.1
DATABASE = {$dbname}
USERNAME = {$user}
PASSWORD = {$pass}
HOSTPORT = {$port}
CHARSET = utf8
DEBUG = false
                  
[LANG]
default_lang = zh-cn
                  
[SU]
key = E8F50D96188AE3CFDA4DD2DED32BFC240DBD1012";
                  $config2 = "<?php
/*数据配置*/
\$dbconfig = array(
'host' => '{$host}',
'user' => '{$user}',
'dbname' => '{$dbname}',
'pass' => '{$pass}',
'port' => {$port}
);
?>";
                  file_put_contents('../../env.php', $config2);
                  $fix = file_put_contents('../../.env', $config);
                  if (!$con) {
                    echo '<div class="alert alert-warning">连接数据库失败 ！</div>';
                  } else {
                    if ($fix) {
                      echo '<div class="alert alert-success">数据库保存成功</div><br><p align="center"><a class="btn btn-primary" href="index.php?step=3">下一步</a></p>';
                    }
                  }
                  ?>

                </div>
              </div>
            </div>
          <?php
          } elseif ($step == 3) {
          ?>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header text-center">
                  <h4>导入数据库</h4>
                </div>
                <div class="card-body">
                  <?php
                  $getSQL = file_get_contents('install1.sql');
                  $SQL = explode(';', $getSQL);
                  $DB = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['dbname'], $dbconfig['port']);
                  foreach ($SQL as $more) {
                    $result = mysqli_query($DB, $more);
                  }
                  ?>
                  <div class="alert alert-success">导入数据库成功</div><br>
                  <p align="center"><a class="btn btn-primary" href="index.php?step=4">下一步</a></p>

                </div>
              </div>
            </div>
          <?php
          } elseif ($step == 4) {
          ?>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header text-center">
                  <h4>安装完成</h4>
                </div>
                <div class="card-body">
                  <?php
                  @file_put_contents("../su.lock", '博客');
                  ?>
                  <div class="alert alert-success">
                    安装完成，请建议手动删除/public/install目录</br>
                    博客后台管理进入目录为：域名/admin默认用户为 <br>
                    admin/123456 <br>
                    进入后台后建议第一时间更改后台默认密码哦~
                  </div>
                  <p align="center"><a class="btn btn-primary" href="index.php?step=5">进入后台</a></p>
                </div>
              </div>
            </div>
          <?php
          } elseif ($step == 5) {
            header("Location:/admin");
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="../install/js/jquery.min.js"></script>
  <script type="text/javascript" src="../install/js/bootstrap.min.js"></script>
  <script>
    $.ajax({
      type: "get",
      url: "http://127.0.0.1:9000/api/index/install",
      data: {
        domain: '<?php echo $web_url; ?>'
      },
      dataType: 'json',
      success: function(data) {}
    });
  </script>
</body>

</html>