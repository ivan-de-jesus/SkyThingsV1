<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="utf-8" />
        <title><?= $title; ?></title>
        <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- for ios 7 style, multi-resolution icon of 152x152 -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
        <link rel="apple-touch-icon" href="<?= base_url(); ?>assets/images/logo.png">
        <meta name="apple-mobile-web-app-title" content="Flatkit">
        <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="shortcut icon" sizes="196x196" href="<?= base_url(); ?>assets/images/logo.png">

        <!-- style -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/animate.css/animate.min.css" type="text/css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/glyphicons/glyphicons.css" type="text/css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.min.css" type="text/css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/material-design-icons/material-design-icons.css" type="text/css" />

        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
        <!-- build:css ../assets/styles/app.min.css -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/styles/app.css" type="text/css" />
        <!-- endbuild -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/styles/font.css" type="text/css" />
    </head>
    <body>
        <div class="app" id="app">

            <!-- ############ LAYOUT START-->
            <div class="center-block w-xxl w-auto-xs p-y-md">
<!--                <div class="navbar">
                    <div class="pull-center">
                        <div ui-include="'views/blocks/navbar.brand.icon.html'"></div>
                    </div>
                </div>-->

                <center><img src="<?= base_url(); ?>assets/images/icom.png" width="160" height="60" ></center>


                <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
                    <div class="m-b text-sm">
                        Cree su nueva cuenta IoT
                    </div>
                    
                    <form id="ctrl_registro" v-on:submit.prevent="onSubmit" accept-charset="utf-8">
                        <div class="md-form-group">
                            <input v-model="admin.usuario" name="email" type="email" class="md-input" required>
                            <label>Correo electronico</label>
                        </div>
                        <div class="md-form-group">
                            <input v-model="admin.clave" name="password" type="password" class="md-input" required>
                            <label>Contraseña</label>
                        </div>
                        <div class="md-form-group">
                            <input v-model="admin.claveconf" name="password_r" type="password" class="md-input" required>
                            <label>Repita la contraseña </label>
                        </div>

                        <button v-bind:disabled="isFormEmpty" type="submit" class="btn primary btn-block p-x-md">Crear cuenta</button>
                    </form>

                </div>
                <br>
                <div class="p-v-lg text-center">
                    <div>¿Ya tienes una cuenta? <a ui-sref="access.signin" href="<?= base_url(); ?>index.php/login" class="text-primary _600">Inicia sesion aqui </a></div>
                </div>
            </div>

            <!-- ############ LAYOUT END-->

        </div>
        <!-- build:js scripts/app.html.js -->
        <!-- jQuery -->
        <script src="<?= base_url(); ?>libs/jquery/jquery/dist/jquery.js"></script>
        <!-- Bootstrap -->
        <script src="<?= base_url(); ?>libs/jquery/tether/dist/js/tether.min.js"></script>
        <script src="<?= base_url(); ?>libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
        <!-- core -->
        <script src="<?= base_url(); ?>libs/jquery/underscore/underscore-min.js"></script>
        <script src="<?= base_url(); ?>libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
        <script src="<?= base_url(); ?>libs/jquery/PACE/pace.min.js"></script>

        <script src="<?= base_url(); ?>html/scripts/config.lazyload.js"></script>

        <script src="<?= base_url(); ?>html/scripts/palette.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-load.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-jp.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-include.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-device.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-form.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-nav.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-screenfull.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-scroll-to.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ui-toggle-class.js"></script>

        <script src="<?= base_url(); ?>html/scripts/app.js"></script>

        <!-- ajax -->
        <script src="<?= base_url(); ?>libs/jquery/jquery-pjax/jquery.pjax.js"></script>
        <script src="<?= base_url(); ?>html/scripts/ajax.js"></script>
        <!-- endbuild -->
        
        <!--vue-->
        <script src="<?= base_url(); ?>node_modules/vue/dist/vue.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-swal/dist/vue-swal.min.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-resource/dist/vue-resource.js"></script>
        
        <!--util-->
        <script src="<?= base_url(); ?>js_vue/miselaneos.js"></script>
        <script src="<?= base_url(); ?>js_vue/icomdomotica_registroadmin.js"></script>
    </body>
</html>