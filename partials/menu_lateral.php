<?php
    include_once $_SESSION['base_url'].'/class/system.php';
    $system = new System;

    $li_mensajes = '<li class="nav-parent">
                                <a>
                                  <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                  <span>Mensajes</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="'.$_SESSION['base_url1'].'app/mensajes/mensajes_recibidos.php'.'">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                            <span>Recibidos</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="'.$_SESSION['base_url1'].'app/mensajes/mensajes_enviados.php'.'">
                                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                            <span>Enviados</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="'.$_SESSION['base_url1'].'app/mensajes/mensaje_nuevo.php'.'">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                            <span>Nuevo Mensaje</span>
                                        </a>
                                      </li>
                                 </ul>
                            </li>';

                $li_qr = '';

?>
<div class="inner-wrapper">
        <!-- start: sidebar -->
        <aside id="sidebar-left" class="sidebar-left">

            <div class="sidebar-header">
                <div class="sidebar-title">
                    Menú
                </div>
                <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>
            <div class="nano">
                <div class="nano-content">
                    <nav id="menu" class="nav-main" role="navigation">
                        <ul class="nav nav-main">
                            <li class="nav-active">
                            <a href="../">
                              <i class="fa fa-home" aria-hidden="true"></i>
                              <span>Inicio</span>
                            </a>
                            </li>
                            <li class="nav">
                              <a href="<?= $_SESSION['base_url1'].'app/admin/lector/index.php' ?>">
                                <i class="fa fa-qrcode" aria-hidden="true"></i>
                                <span>Lector QR</span>
                              </a>
                            </li>
<? if($_SESSION["nivel"] < 2){ // Menú administrativo?>
                                <li class="nav-parent">
                                <a>
                                  <i class="fa fa-users" aria-hidden="true"></i>
                                  <span>Usuarios</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/vista_usuarios.php?a='.base64_encode("USUARIOS").'&b='.base64_encode("USUARIOS ESTADALES").'' ?>">
                                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                            <span>Estadales</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/vista_usuarios2.php?a='.base64_encode("USUARIOS").'&b='.base64_encode("USUARIOS MUNICIPALES").'' ?>">
                                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                            <span>Municipales</span>
                                        </a>
                                      </li>
                                  </ul>
                                </li>
                               <li class="nav-parent">
                                <a>
                                  <i class="fa fa-car" aria-hidden="true"></i>
                                  <span>Líneas de Transporte</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/vista_lineas_gen.php?a='.base64_encode("LÍNEAS DE TRANSPORTE").'&b='.base64_encode("LISTADO").'' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de líneas</span>
                                        </a>
                                      </li>
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/transportistas/index.php?a='.base64_encode("TRANSPORTISTAS").'&b='.base64_encode("LISTADO").'' ?>">
                                      <i class="fa fa-list" aria-hidden="true"></i>
                                      <span>Listado de Transportistas</span>
                                  </a>
                                </li>
                                 <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/vista_unidades_gen.php?a='.base64_encode("TRANSPORTISTAS").'&b='.base64_encode("LISTADO").'' ?>">
                                      <i class="fa fa-list" aria-hidden="true"></i>
                                      <span>Listado de Transportistas</span>
                                  </a>
                                </li>
                                </ul>
                            </li>
                            <li class="nav-parent">
                              <a>
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span>Marcas y Modelos</span>
                              </a>
                              <ul class="nav nav-children">
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_marcas.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MARCAS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Marcas de VehÍculos</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_modelos.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MODELOS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Modelos de VehÍculos</span>
                                  </a>
                                </li>
                              </ul>
                            </li>

                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                  <span>Rutas</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/vista_rutas.php?a='.base64_encode("RUTAS").'&b='.base64_encode("LISTADO DE RUTAS").'' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de rutas</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <!--<li class="nav-parent">
                                <a>
                                  <i class="fa fa-mail-forward" aria-hidden="true"></i>
                                  <span>Despacho</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li>
                                      <a href="<?= $_SESSION['base_url1'].'app/admin/index.php' ?>">
                                          <i class="fa fa-building" aria-hidden="true"></i>
                                          <span>Proveedurías</span>
                                      </a>
                                    </li>
                                    <li>
                                      <a href="<?=  $_SESSION['base_url1'].'app/admin/index.php' ?>">
                                          <i class="fa fa-building" aria-hidden="true"></i>
                                          <span>Tiendas Asociadas</span>
                                      </a>
                                    </li>
                                </ul>
                              </li>-->
                            <?= $li_mensajes ?>
                              <!--<li class="nav-parent">
                                <a>
                                  <i class="fa fa-book" aria-hidden="true"></i>
                                  <span>Inventario</span>
                                </a>
                                <ul class="nav nav-children">
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/inventario/ver_inventario.php' ?>">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span>Ver Inventario</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/inventario/importar_inventario.php' ?>">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span>Importar Inventario</span>
                                    </a>
                                  </li>
                                </ul>
                            </li>-->

                          <!--Agregar menu nuevo-->
                          <li class="nav-parent">
                                <a>
                                  <i class="fa fa-archive" aria-hidden="true"></i>
                                  <span>Manejo del Producto</span>
                                </a>
                                <ul class="nav nav-children">
                                  <li>
                                      <a href="<?= $_SESSION['base_url1'].'app/admin/producto/almacenes.php?l='.base64_encode('all'); ?>">
                                          <i class="fa fa-list" aria-hidden="true"></i>
                                          <span>Almacenes (Ver almacenes)</span>
                                      </a>
                                    </li>
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/producto/add_entrada_pro.php' ?>">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span>Registrar Entrada</span>
                                    </a>
                                  </li>
                                  <!--<li>
                                    <a href="#">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span>Traslado de Productos</span>
                                    </a>
                                  </li>-->
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/inventario/ver_inventario.php' ?>">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span>Ver Inventario</span>
                                    </a>
                                  </li>
                                  <li class="nav-parent">
                                    <a>
                                      <i class="fa fa-asterisk" aria-hidden="true"></i>
                                      <span>Tablas de configuración</span>
                                    </a>
                                    <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_producto.php' ?>">
                                          <i class="fa fa-asterisk" aria-hidden="true"></i>
                                          <span>Productos</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_almacenes.php' ?>">
                                          <i class="fa fa-asterisk" aria-hidden="true"></i>
                                          <span>Almacenes</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_tipos_subtipos.php' ?>">
                                          <i class="fa fa-asterisk" aria-hidden="true"></i>
                                          <span>Tipos y Sub-Tipos</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_procedencias.php' ?>">
                                          <i class="fa fa-asterisk" aria-hidden="true"></i>
                                          <span>Procedencias</span>
                                        </a>
                                      </li>
                                    </ul>
                                  </li>

                                </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-book" aria-hidden="true"></i>
                                  <span>Solicitudes</span>
                                </a>
                                <ul class="nav nav-children">
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/solicitudes/ver_solicitudes.php' ?>">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                        <span>Ver Solicitudes</span>
                                    </a>
                                  </li>
                                </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-truck" aria-hidden="true"></i>
                                  <span>Asignaciones</span>
                                </a>
                                <ul class="nav nav-children">
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/asig_items_directa.php' ?>">
                                        <i class="fa fa-hand-o-up" aria-hidden="true"></i>
                                        <span>Asignacion Directa</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/asig_items_estados.php' ?>">
                                        <i class="fa fa-university" aria-hidden="true"></i>
                                        <span>Consignar a Estados</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones.php' ?>">
                                        <i class="fa fa-check-square" aria-hidden="true"></i>
                                        <span>Confirmar Preasignaciones</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/asig_items_ver.php' ?>">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span>Ver asignaciones</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/admin/despacho/index.php' ?>">
                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        <span>Despacho</span>
                                    </a>
                                  </li>
                                  </li>
                                </ul>
                            </li>
                    <? } if($_SESSION["nivel"] == 2){ // menú estadal?>
                              <li class="nav-parent">
                                  <a>
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span>Usuarios</span>
                                  </a>
                                <ul class="nav nav-children">
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/estado/vista_usuarios.php' ?>">
                                      <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                      <span>Municipales</span>
                                    </a>
                                  </li>
                                </ul>
                              </li>
                              <li class="nav-parent">
                              <a>
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span>Marcas y Modelos</span>
                              </a>
                              <ul class="nav nav-children">
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_marcas.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MARCAS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Marcas de VehÍculos</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_modelos.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MODELOS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Modelos de VehÍculos</span>
                                  </a>
                                </li>
                              </ul>
                            </li>
                            <li class="nav-parent">
                              <a>
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span>Líneas de Transporte</span>
                              </a>
                              <ul class="nav nav-children">
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/estado/vista_lineas.php' ?>">
                                      <i class="fa fa-list" aria-hidden="true"></i>
                                      <span>Listado de líneas</span>
                                  </a>
                                </li>
                              </ul>
                            </li>
                            <li class="nav-parent">
                              <a>
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                <span>Rutas</span>
                              </a>
                              <ul class="nav nav-children">
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/estado/vista_rutas.php' ?>">
                                      <i class="fa fa-list" aria-hidden="true"></i>
                                      <span>Listado de rutas</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="#">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <span>Incluir ruta inter estadal</span>
                                  </a>
                                </li>
                              </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-mail-forward" aria-hidden="true"></i>
                                  <span>Despacho</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/estado/index.php' ?>">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                            <span>Proveedurías</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/estado/index.php' ?>">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                            <span>Tiendas Asociadas</span>
                                        </a>
                                        </li>
                                 </ul>
                            </li>
                            <?= $li_mensajes ?>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-book" aria-hidden="true"></i>
                                  <span>Solicitudes</span>
                                </a>
                                <ul class="nav nav-children">
                                  <li>
                                    <a href="<?= $_SESSION['base_url1'].'app/estado/solicitudes/ver_solicitudes.php' ?>">
                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                        <span>Ver Solicitudes</span>
                                    </a>
                                  </li>
                                </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                  <span>Asignaciones</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/estado/asignaciones/asignaciones_for_estado.php' ?>">
                                            <i class="fa fa-globe" aria-hidden="true"></i>
                                            <span>Consignaciones al Estado</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/estado/asignaciones/asignaciones_for_edit.php' ?>">
                                            <i class="fa fa-bank" aria-hidden="true"></i>
                                            <span>Preasignaciones Rechazadas</span>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/estado/asignaciones/asignaciones_for_ver.php' ?>">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            <span>Asignaciones</span>
                                        </a>
                                      </li>
                                 </ul>
                            </li>
<? } if($_SESSION["nivel"] == 3) { // menú municipal?>
                                <li class="nav-parent">
                              <a>
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span>Marcas y Modelos</span>
                              </a>
                              <ul class="nav nav-children">
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_marcas.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MARCAS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Marcas de VehÍculos</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_modelos.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MODELOS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Modelos de VehÍculos</span>
                                  </a>
                                </li>
                              </ul>
                            </li>
                                <li class="nav-parent">
                                <a>
                                  <i class="fa fa-car" aria-hidden="true"></i>
                                  <span>Líneas de Transporte</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/municipio/vista.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de líneas</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/municipio/incluir_linea.php' ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir línea de transporte</span>
                                        </a>
                                      </li>
                                  </ul>
                                </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                  <span>Rutas</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/municipio/vista_rutas_mun.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de rutas</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/municipio/incluir_rutas.php' ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir rutas</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                  <span>Documentos</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'assets/docs/planilla_operadoras.pdf' ?>" target="_blank">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            <span>Planilla de Registro operadoras</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <?= $li_mensajes ?>

<? } if($_SESSION["nivel"] ==4) { // menú líneas de transporte?>
                               <li class="nav-parent">
                                <a>
                                  <i class="fa fa-users" aria-hidden="true"></i>
                                  <span>Afiliados</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/ltransporte/incluir_afiliado.php' ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir Afiliado</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/ltransporte/vista.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Lista de afiliados</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <li class="nav-parent">
                              <a>
                                <i class="fa fa-car" aria-hidden="true"></i>
                                <span>Marcas y Modelos</span>
                              </a>
                              <ul class="nav nav-children">
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_marcas.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MARCAS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Marcas de VehÍculos</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?= $_SESSION['base_url1'].'app/admin/agregar_modelos.php?a='.base64_encode("MARCAS Y MODELOS").'&b='.base64_encode("MODELOS DE VEHÍCULOS").'' ?>">
                                      <i class="fa fa-plus" aria-hidden="true"></i>
                                      <span>Agregar Modelos de VehÍculos</span>
                                  </a>
                                </li>
                              </ul>
                            </li>
                               <li class="nav-parent">
                                <a>
                                  <i class="fa fa-car" aria-hidden="true"></i>
                                  <span>Unidades</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/ltransporte/vista_unidades.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de unidades</span>
                                        </a>
                                      </li>
                                  </ul>
                                </li>
                                <li class="nav-parent">
                                    <a>
                                      <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                      <span>Documentos</span>
                                    </a>
                                    <ul class="nav nav-children">
                                          <li>
                                            <a href="<?= $_SESSION['base_url1'].'assets/docs/planilla_asociados.pdf' ?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                <span>Planilla de Registro de Asociados</span>
                                            </a>
                                          </li>
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/ltransporte/reportes/rep_socios.php' ?>" target="_blank">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            <span>Listado de Socios</span>
                                        </a>
                                      </li>
                                      </ul>
                                </li>
                            <?= $li_mensajes ?>

<? } if($_SESSION["nivel"] == 5){ // menú afiliados o transportistas?>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-user" aria-hidden="true"></i>
                                  <span>Unidades de Transporte</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/incluir_conduc.php' ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir Conductor</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de Unidades</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_conductores.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado Conductores</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-users" aria-hidden="true"></i>
                                  <span>Carga Familiar</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/incluir_carga_familiar.php?agregar='.base64_encode($_SESSION['user']) ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir Carga Familiar</span>
                                        </a>
                                      </li>
                                     <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_carga.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Carga Familiar</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <!--menu solicitudes-->
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-copy" aria-hidden="true"></i>
                                  <span>Solicitudes</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/incluir_solicitud.php'?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Crear Solicitud</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_solicitudes.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de sus Solicitudes</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_asig.php' ?>">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                            <span>Imprimir asignaciones</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <!--menu solicitudes-->
                            <?= $li_mensajes ?>

<? } if($_SESSION["nivel"] == 6){ // menú Almacenes?>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-user" aria-hidden="true"></i>
                                  <span>Unidades de Transporte</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/incluir_conduc.php' ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir Conductor</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de Unidades</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_conductores.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado Conductores</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-users" aria-hidden="true"></i>
                                  <span>Carga Familiar</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/incluir_carga_familiar.php?agregar='.base64_encode($_SESSION['user']) ?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Incluir Carga Familiar</span>
                                        </a>
                                      </li>
                                     <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_carga.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Carga Familiar</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <!--menu solicitudes-->
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-copy" aria-hidden="true"></i>
                                  <span>Solicitudes</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/incluir_solicitud.php'?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Crear Solicitud</span>
                                        </a>
                                      </li>
                                       <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/usuario/vista_solicitudes.php' ?>">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                            <span>Listado de sus Solicitudes</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-copy" aria-hidden="true"></i>
                                  <span>Inventario</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/almacen/inventario/index.php'?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Ver Inventario</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <li class="nav-parent">
                                <a>
                                  <i class="fa fa-copy" aria-hidden="true"></i>
                                  <span>Despacho</span>
                                </a>
                                <ul class="nav nav-children">
                                      <li>
                                        <a href="<?= $_SESSION['base_url1'].'app/almacen/beneficiados/index.php'?>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <span>Ver Beneficiados</span>
                                        </a>
                                      </li>
                                  </ul>
                            </li>
                            <!--menu solicitudes-->
                            <?= $li_mensajes ?>
<? } ?>
                       </ul>
                    </nav>
                </div>
            </div>
        </aside>
