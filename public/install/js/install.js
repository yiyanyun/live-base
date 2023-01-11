/*
 * @Author       : Lucifer
 * @Date         : 2022-12-26 16:50:20
 * @LastEditTime : 2022-12-26 16:51:56
 * @FilePath     : \ioucode_auth\public\install\js\install.js
 */
$.ajax({ cache: false, type: "POST", url: "http://127.0.0.1:9000/api/index/install", data: { domain: '<?php echo $web_url;?>' }, dataType: 'json', success: function (data) { } });

