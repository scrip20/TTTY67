@extends('layouts.app')

@section('content')
<div class="profile-page">
    <div class="container">

        <!-- inicio del elemento de superposición de carga -->
        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>
        <!-- fin del elemento de superposición de carga -->

        <div class="row">

            <!-- inicio del título de la página -->
            <div class="row m-5">
                <div class="page-title">
                    <span class="dash-profile"></span>
                    <span class="title">
                        Información Personal
                    </span>
                </div>
            </div>
            <!-- fin del título de la página -->

            <!-- inicio de la tarjeta de perfil -->
            <div class="row profile-card mt-3 mb-3">

                <!-- sección de foto de perfil -->
                <div class="col-lg-6 ">
                    <!-- mensaje de error de carga de foto -->
                    <div id="error-image-message" class="alert-danger rounded text-center m-2"></div>
                    <div class="profile-img">
                        <span id="edit_profile_photo" onclick="editMedia('image', '/editProfilePhoto')">
                            <i class="fa fa-camera camera-icon"></i>
                        </span>
                        <img class="img-fluid shadow-sm" height="300px" id="profile_photo" src="{{ asset('storage/upload/profiles_photos/' . Auth::user()->image) }}" alt="">
                    </div>
                </div>

                <!-- sección de datos del perfil -->
                <div class="col-lg-6 ">
                    <div class="profile-data">
                        <div class="mx-5 mb-4">
                            <div class="form-group">
                                <div class="col-12 mt-4 mb-3">
                                    <label for="user_name" class="form-label">Nombre</label>
                                    <div class="row justify-content-center align-items-center gx-2">
                                        <div class="col-8 ">
                                            <div class="data-label" id="user_name">{{ Auth::user()->name }}</div>
                                        </div>
                                        <div class="col-4 ">
                                            <a class="btn change-pass-btn edit_name" id="edit_name" role="button" data-bs-toggle="modal" data-bs-target="#edit_name_pop">
                                                <i class="fa fa-pen ps-2"></i>
                                                <span>
                                                    Editar
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="user_email" class="form-label user-email">Correo Electrónico</label>
                                <div class="data-label user-email">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="user_pass" class="form-label user-pass">Contraseña</label>
                                <div class="data-label user-pass">**************</div>
                            </div>
                        </div>
                        <a class="btn update-profile-btn" role="button" data-bs-toggle="modal" data-bs-target="#change_password">
                            <span>
                                Cambiar Contraseña
                            </span>
                            <i class="fa fa-pen pe-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- fin de la tarjeta de perfil -->

        </div>
    </div>
</div>

{{-- popup editar nombre --}}
<div class="modal fade" tabindex="-1" id="edit_name_pop" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Nombre</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_name_form">
                @csrf
                <div class="modal-body mx-5 mb-4">
                    <div class="form-group">
                        <div class="col-12 mt-4 mb-3">
                            <label for="usernameEditInput" class="form-label manager-name">Nombre de Usuario</label>
                            <input type="text" required class="form-control" name="username" id="usernameEditInput" placeholder="Mohamed Shawabi" autofocus value="{{ Auth::user()->name }}">
                            <span class="invalid-feedback" role="alert" id="usernameEditError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
               
                <div class="modal-footer justify-content-evenly">
                    <button type="submit" class="btn save" id="delete_btn">Guardar</button>
                    <button type="button" class="btn btn-secondary cancel" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- popup cambiar contraseña --}}
<div class="modal fade modal-md" id="change_password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="change_password_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-4">
                <span class="icon-bordered fs-4" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-close"></i></span>
                <h5 class="modal-title text-center w-100" id="change_password_label">Cambiar Contraseña</h5>
            </div>
            <form id="change_pass">
                @csrf
                <div class="modal-body mx-5 mb-4">
                    <div class="form-group">
                        <div class="col-12 mt-4 mb-3">
                            <label for="old_password" class="form-label old-pas">Contraseña Antigua</label>
                            <input type="password" required class="form-control" name="old_password" id="old_passwordInput">
                            <span class="invalid-feedback" role="alert" id="old_passwordError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="new_password" class="form-label manager-name new-password">Nueva Contraseña</label>
                        <input type="password" required class="form-control" name="new_password" id="new_passwordInput">
                        <span class="invalid-feedback" role="alert" id="new_passwordError">
                            <strong></strong>
                        </span>
                    </div>
                    <div class="col-12">
                        <label for="new_password_confirmationInput" class="form-label manager-name">Confirmar Nueva Contraseña</label>
                        <input type="password" required class="form-control" name="new_password_confirmation" id="new_password_confirmationInput">
                        <span class="invalid-feedback" role="alert" id="new_password_confirmationError">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-evenly mb-4">
                    <input type="submit" id="submit" class="save btn" value="Guardar" />
                    <input type="reset" class="cancel btn btn-secondary" data-bs-dismiss="modal" value="Cancelar" />
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
