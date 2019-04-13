<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?= $this->Html->script('jquery-3.4.0.min.js'); ?>
    <script>
        function jsonForm (url, form){
            $.ajax({
                type: "POST",
                url: url,
                cache: false,
                data: form.serializeArray(),
                dataType: 'json',
                headers : {'X-CSRF-Token':  <?= json_encode($this->request->param('_csrfToken')); ?>},
                success: data =>{
                    $('#ajaxResponse').text(data.message)
                },
                error: data =>{
                    $('#ajaxResponse').text(data.message)
                },
            })
        }

        function sendJson(url, type = "POST",  data = [], successCallback = null, errorCallback = null){
            $.ajax({
                type: type,
                url: url,
                cache: false,
                data: data,
                dataType: 'json',
                headers : {'X-CSRF-Token':  <?= json_encode($this->request->param('_csrfToken')); ?>},
                success: successCallback,
                error: errorCallback,
            })
        }
    </script>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><a href="<?= $this->Url->build('/', ['escape' => false,'fullBase' => true,]);?>">Demo app</a></h1>
            </li>
        </ul>
    </nav>
    <div id = "ajaxResponse"></div>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
