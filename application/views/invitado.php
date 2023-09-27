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
        <!-- build:css assets/styles/app.min.css -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/styles/app.css" type="text/css" />
        <!-- endbuild -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/styles/font.css" type="text/css" />
        <link href="<?= base_url(); ?>assets/styles/iotronics.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="app" id="app">
            <div id="ctrl_invitado">
                <!-- ############ LAYOUT START-->

                <!-- BARRA IZQUIERDA -->
                <!-- aside -->
                <menu-izquierdo v-bind:tipousuario="tipousuario" v-bind:usuario="usuario"></menu-izquierdo>
                <!-- / -->

                <!-- content -->
                <div id="content" class="app-content box-shadow-z0" role="main">
                    
                    <encabezado titulo="Invitados"></encabezado>

                    <!-- PIE DE PAGINA -->
                    <pie-pagina></pie-pagina>

                    <div ui-view class="app-body" id="view">

                        <!-- SECCION CENTRAL -->
                        <div class="padding">
                            <!-- SWItCH1 y 6 -->
                            <div class="row">
                                <!-- SWItCH1 -->
                                <div class="col-xs-12 col-sm-4">
                                    <div class="box p-a">
                                        <div class="box-header">
                                            <h2>Nuevo invitado</h2>
                                        </div>
                                        <div class="box-body">
                                            <form v-on:submit.prevent="onSubmit" v-on:reset.prevent="limpiar" accept-charset="utf-8">
                                                <div class="form-group">
                                                    <label>Usuario</label>
                                                    <input type="text" v-model="invitado.usuario" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Contrase&ntilde;a temporal</label>
                                                    <div class="input-group">
                                                        <input type="text" v-model="invitado.clave" class="form-control">
                                                        <div class="input-group-append">
                                                            <button type="button" v-on:click="onGenContrasenia" class="btn btn-danger">Generar</button>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Esta contrase&ntilde;a es temporal, el invitado a quien se la asigne 
                                                        debe cambiarla, al iniciar sesion por primera vez.</small>
                                                </div>
                                                <button type="reset" class="btn btn-secondary">Limpiar</button>
                                                <button type="submit" v-bind:disabled="isFormEmpty" class="btn btn-primary">Aceptar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-8">
                                    <div class="box p-a">
                                        <div class="box-header">
                                            <h2>Invitados</h2>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div v-if="invitados.length === 0" class="col-xs-12 col-sm-12">
                                                    <div class="card card-danger p-a text-white">
                                                        <div class="card-body">
                                                            <div class="card-text"><i class="fa fa-close"></i> No se encontraron invitados registrados</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-for="item in invitados" v-bind:key="item.id" class="col-xs-12 col-sm-4">
                                                    <div class="card p-a m-b" v-bind:class="classItem(item.primerlogin)">
                                                        <div class="card-body text-dark">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-lg-12">
                                                                    <div class="card-text text-muted">Usuario</div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-lg-12">
                                                                    <span> {{ item.usuario }} </span>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-lg-12">
                                                                    <div class="card-text text-muted">Ingres&oacute; por primera vez?</div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-12 col-lg-12">
                                                                    <span class="badge badge-pill" v-bind:class="classItemBadge(item.primerlogin)"> {{ item.primerlogin }} </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <vue-snotify></vue-snotify>

                        </div>

                        <!-- ############ PAGE END-->

                    </div>

                </div>
                <!-- / -->

                <!-- SELECTOR DE TEMAS -->
                <div id="switcher">
                    <div class="switcher box-color dark-white text-color" id="sw-theme">
                        <a href ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
                            <i class="fa fa-gear"></i>
                        </a>
                        <div class="box-header">
                            <h2>Theme Switcher</h2>
                        </div>
                        <div class="box-divider"></div>
                        <div class="box-body">
                            <p class="hidden-md-down">
                                <label class="md-check m-y-xs"  data-target="folded">
                                    <input type="checkbox">
                                    <i class="green"></i>
                                    <span class="hidden-folded">Folded Aside</span>
                                </label>
                                <label class="md-check m-y-xs" data-target="boxed">
                                    <input type="checkbox">
                                    <i class="green"></i>
                                    <span class="hidden-folded">Boxed Layout</span>
                                </label>
                                <label class="m-y-xs pointer" ui-fullscreen>
                                    <span class="fa fa-expand fa-fw m-r-xs"></span>
                                    <span>Fullscreen Mode</span>
                                </label>
                            </p>
                            <p>Colors:</p>
                            <p data-target="themeID">
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'primary', accent:'accent', warn:'warn'}">
                                    <input type="radio" name="color" value="1">
                                    <i class="primary"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'accent', accent:'cyan', warn:'warn'}">
                                    <input type="radio" name="color" value="2">
                                    <i class="accent"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'warn', accent:'light-blue', warn:'warning'}">
                                    <input type="radio" name="color" value="3">
                                    <i class="warn"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'success', accent:'teal', warn:'lime'}">
                                    <input type="radio" name="color" value="4">
                                    <i class="success"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'info', accent:'light-blue', warn:'success'}">
                                    <input type="radio" name="color" value="5">
                                    <i class="info"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'blue', accent:'indigo', warn:'primary'}">
                                    <input type="radio" name="color" value="6">
                                    <i class="blue"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'warning', accent:'grey-100', warn:'success'}">
                                    <input type="radio" name="color" value="7">
                                    <i class="warning"></i>
                                </label>
                                <label class="radio radio-inline m-0 ui-check ui-check-color ui-check-md" data-value="{primary:'danger', accent:'grey-100', warn:'grey-300'}">
                                    <input type="radio" name="color" value="8">
                                    <i class="danger"></i>
                                </label>
                            </p>
                            <p>Themes:</p>
                            <div data-target="bg" class="row no-gutter text-u-c text-center _600 clearfix">
                                <label class="p-a col-sm-6 light pointer m-0">
                                    <input type="radio" name="theme" value="" hidden>
                                    Light
                                </label>
                                <label class="p-a col-sm-6 grey pointer m-0">
                                    <input type="radio" name="theme" value="grey" hidden>
                                    Grey
                                </label>
                                <label class="p-a col-sm-6 dark pointer m-0">
                                    <input type="radio" name="theme" value="dark" hidden>
                                    Dark
                                </label>
                                <label class="p-a col-sm-6 black pointer m-0">
                                    <input type="radio" name="theme" value="black" hidden>
                                    Black
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / -->

                <!-- ############ LAYOUT END-->
            </div>
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
        <!--<script src="<?= base_url(); ?>libs/jquery/jquery-pjax/jquery.pjax.js"></script>-->
        <!--<script src="<?= base_url(); ?>html/scripts/ajax.js"></script>-->

        <!--<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>-->
        <!--<script src="<?= base_url(); ?>js/socket_mqtt.js"></script>-->
        <!-- endbuild -->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->

        <!--vue-->
        <script src="<?= base_url(); ?>node_modules/vue/dist/vue.js"></script>
        <script src="<?= base_url(); ?>node_modules/vuex/dist/vuex.min.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-swal/dist/vue-swal.min.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-snotify/vue-snotify.min.js"></script>
        <script src="<?= base_url(); ?>node_modules/vue-resource/dist/vue-resource.js"></script>
        <script src="<?= base_url(); ?>node_modules/mqtt/dist/mqtt.min.js"></script>

        <!--util-->
        <script src="<?= base_url(); ?>js_vue/miselaneos.js"></script>
        <script src="<?= base_url(); ?>js_vue/store.js"></script>
        <script src="<?= base_url(); ?>js_vue/componente/menu-izquierdo.js"></script>
        <script src="<?= base_url(); ?>js_vue/componente/encabezado.js"></script>
        <script src="<?= base_url(); ?>js_vue/componente/pie-pagina.js"></script>
        <script src="<?= base_url(); ?>js_vue/icomdomotica_invitado.js"></script>
    </body>
</html>