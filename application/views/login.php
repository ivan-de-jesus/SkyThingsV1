<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="utf-8" />
        <title><?= $title; ?></title>
        <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="description" content="Bienvenid@s a IoTronics, Empresa dedicada al desarrollo de aplicativos IoT.)">

        <meta name="theme-color" content="#0a83f5">
        <meta name="MobileOptimized" content="width">
        <meta name="HandheldFriendly" content="true">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>img/icom.png">
        <link rel="apple-touch-icon" href="<?= base_url(); ?>img/icom.png">
        <link rel="apple-touch-startup-image" href="<?= base_url(); ?>img/icom.png">
        <link rel="manifest" href="manifest.json">

        <!-- for ios 7 style, multi-resolution icon of 152x152 -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
        <link rel="apple-touch-icon" href="<?= base_url(); ?>assets/images/icom.png">
        <meta name="apple-mobile-web-app-title" content="Flatkit">
        <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="shortcut icon" sizes="196x196" href="<?= base_url(); ?>assets/images/icom.png">

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
        <link href="<?= base_url(); ?>node_modules/vue-snotify/styles/material.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="app" id="app">

            <!-- ############ LAYOUT START-->
            <div class="center-block w-xxl w-auto-xs p-y-md">
                <div class="navbar">
                    <center><img src="<?= base_url(); ?>assets/images/icom.png" width="160" height="60"></center>
                </div>
            </div>
            <div class="p-a-md box-color r box-shadow-z1 text-color center-block w-xxl w-auto-xs p-y-md">
                <div class="m-b text-sm">
                    Inicie sesión con su cuenta de IoT
                </div>

                <form id="ctrl_login" v-on:submit.prevent="onSubmit" accept-charset="utf-8" >
                    <div class="md-form-group float-label">
                        <input v-model="sesion.usuario" name="email" type="email" class="md-input" value="" required >
                        <label>Email</label>
                    </div>
                    <div  class="md-form-group float-label">
                        <input v-model="sesion.clave" name="password" type="password" class="md-input" required >
                        <label>Password</label>
                    </div>

                    <div class="row"> 
                        <div class="col-sm-12">
                            <label>Ingresar como</label>
                        </div>
                        <div class=" col-sm-6">
                            <label><input v-model="sesion.tipo" type="radio" id="tipo" value="administrador" checked=""> Administrador</label>
                        </div>

                        <div class=" col-sm-6">
                            <label><input v-model="sesion.tipo" type="radio" id="tipo" value="invitado"> Invitado</label>
                        </div>
                    </div>

                    <button type="submit" v-bind:disabled="isFormEmpty" class="btn primary btn-block p-x-md">Iniciar sesion</button>
                    <input type="checkbox" name="chk_rec" value="chk_rec">  Recordar Usuario y Contraseña
                </form>
            </div>

            <div class="p-v-lg text-center">
                <div class="m-b"><a ui-sref="access.forgot-password" href="forgot-password.html" class="text-primary _600">¿Se te olvidó tu contraseña?</a></div>
                <div>¿No tiene una cuenta? <a ui-sref="access.signup" href="<?= base_url(); ?>index.php/login/registro" class="text-primary _600">Regístrate</a></div>
            </div>
        </div>

        <div id="ctrl_cambio">
            <div id="modalCambio" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                <div role="document" class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 id="exampleModalLabel" class="modal-title">Primer login</h5>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <p>Antes de ingresar por primera vez, debe cambiar su contrase&ntilde;a</p>
                            <form accept-charset="utf-8">
                                <div class="form-group">
                                    <label>Usuario</label>
                                    <input v-model="invitado.usuario" name="user_mqtt" type="text" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Contraseña actual</label>
                                    <input v-model="invitado.actual" name="pass_mqtt" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Contraseña nueva</label>
                                    <input v-model="invitado.nueva" name="pass_mqtt_n" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Repita la contraseña nueva</label>
                                    <input v-model="invitado.nuevaconf" name="pass_mqtt_n_r" type="password" class="form-control" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                            <button type="button" v-bind:disabled="isFormEmpty" v-on:click="onSubmit" class="btn btn-danger">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <vue-snotify></vue-snotify>
        </div>
        <!-- ############ LAYOUT END-->
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
        <!--<script src="<?= base_url(); ?>libs/jquery/jquery-pjax/jquery.pjax.js"></script>-->
        <!--<script src="<?= base_url(); ?>html/scripts/ajax.js"></script>-->

        <!-- endbuild -->

        <!--vue-->
        <script src="<?= base_url(); ?>node_modules/vue/dist/vue.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-swal/dist/vue-swal.min.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-snotify/vue-snotify.min.js" type="text/javascript"></script>
        <script src="<?= base_url(); ?>node_modules/vue-resource/dist/vue-resource.js"></script>
        <script src="<?= base_url(); ?>node_modules/mqtt/dist/mqtt.min.js"></script>

        <!--util-->
        <script src="<?= base_url(); ?>js_vue/miselaneos.js" type="text/javascript"></script>
        <script src="<?= base_url(); ?>js_vue/icomdomotica_login.js"></script>

    </body>
</html>