@extends('layouts.app')

@section('content')
    <div class="container-fluid full-page">

        <!-- inicio del elemento de superposición de carga -->
        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>
        <!-- fin del elemento de superposición de carga -->

        <!-- inicio de la sección del título de la página -->
        <div class="row text-center mt-4">
            <div class="page-title">
                Gestión de Administradores
            </div>
        </div>
        <!-- fin de la sección del título de la página -->


        <!-- inicio de la sección de búsqueda de administrador -->
        <div class="row mt-4 d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <form method="get" action="{{ route('manage') }}">
                    <div class="search form-group">
                        <span class="search-icon icon-bordered">
                            <i class="fa-solid fa-magnifying-glass fa-flip-horizontal" id="search_icon"
                                style="--fa-animation-duration: 1s;"></i>
                        </span>
                        <input type="text" value="{{ $search }}" name="search" id="search_txt"
                            oninput="performSearch('/manage', { search: this.value.trim() } )" autocomplete="off"
                            class="form-control shadow-none" placeholder="Buscar un administrador...">
                        <input type="submit" id="search_btn" value="Buscar" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <!-- lista de resultados de búsqueda  -->
            <div class="col-md-9">
                <div class="list-group" id="result_list"></div>
            </div>
        </div>
        <!-- fin de la sección de búsqueda de administrador -->


        <!-- inicio del botón flotante de agregar -->
        <a class="btn overflow-visible add-btn text-white shadow" href="#" role="button" data-bs-toggle="modal"
            data-bs-target="#add_manager">
            <i class="fa fa-add"></i>
            <span style="display: none;">
                Añadir Administrador
            </span>
        </a>
        <!-- fin del botón flotante de agregar -->

        <div class="container">

            @if (count($admins) > 0)
                <!-- inicio de la sección del título de administrador -->
                <div class="list-title py-3 pe-4 mt-5 mb-4">
                    <div class="row text-center">
                        <div class="col-lg-3">Nombre</div>
                        <div class="col-lg-3">Correo Electrónico</div>
                        <div class="col-lg-3">Estado</div>
                        <div class="col-lg-3">Acción</div>
                    </div>
                </div>
                <!-- fin de la sección del título de administrador -->

                <!-- inicio de la sección de lista de administradores -->
                <div id="list_content">

                    {{-- bucle a través de todos los administradores --}}
                    @foreach ($admins as $admin)
                        <div class="list-content py-4 pe-4 mt-4 mb-3" id="list_content">
                            <div class="row justify-content-center align-items-center text-center">
                                <div class="col-lg-3 text-lg-left">
                                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                        <img src="{{ asset('storage/upload/profiles_photos/thumbs/' . $admin->image) }}"
                                            alt="Logo" id="round-profile" class="img-fluid" />
                                        <span class="pe-2 user-name">{{ $admin->name }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 user-email">{{ $admin->email }}</div>
                                <div class="col-lg-3 user-statue d-flex justify-content-center align-items-center">
                                    <input type="checkbox" class="toggle-class" data-id="{{ $admin->id }}"
                                        id="{{ $admin->id }}" {{ $admin->locked ? 'checked' : '' }}>
                                    <label for="{{ $admin->id }}"></label>
                                    <span class="me-2"></span>
                                </div>
                                <div class="col-lg-3 user-pros">
                                    <a class="delete_popup text-decoration-none" id="delete_popup"
                                        onclick="editAdmin({{ $admin->id }},'{{ $admin->name }}' , '{{ $admin->email }}')">
                                        <i class="fa fa-pen "></i>
                                    </a>
                                    <a class="delete_popup text-decoration-none" id="delete_popup"
                                        onclick="deletePopup({{ $admin->id }},'delete_manager','admin
_id')">
                                        <i class="fa fa-trash-can pe-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- fin de la sección de lista de administradores -->

        </div>

        <!-- mostrar un mensaje si no hay administradores -->
    @else
        <div class="container text-center">

            @if (request()->route()->getName() === 'manage' && !request()->has('search'))
                <h1 class="mb-0 pb-0 pt-5 text-muted">¡No se ha añadido ningún administrador hasta ahora!</h1>
            @elseif(request()->route()->getName() === 'manage' && request()->has('search'))
                <h1 class="mb-0 pb-0 pt-5 text-muted">¡No existe un administrador con ese nombre!</h1>
            @endif

            <img src="{{ asset('storage/upload/No_data.svg') }}" class="img-fluid w-75 w">
        </div>
        @endif
    </div>

    {{-- popup para añadir administrador --}}
    <div class="modal fade modal-md" id="add_manager" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="add_manager_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-4">
                    <span class="icon-bordered fs-4" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-close"></i></span>
                    <h5 class="modal-title text-center w-100" id="add_manager_label">Añadir Administrador</h5>
                </div>
                <form method="POST" id="manager_form">
                    <div class="modal-body  mx-5 mb-4">
                        @csrf

                        <div class="form-group">
                            <div class="col-12 mt-4 mb-3">
                                <label for="usernameInput" class="form-label manager-name">Nombre de Usuario</label>
                                <input type="text" class="form-control" name="username" id="usernameInput" required
                                    placeholder="Mohamed Shawabi" autofocus>
                                <span class="invalid-feedback" role="alert" id="usernameError">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="emailInput" class="form-label manager-email">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" id="emailInput" required
                                placeholder="ejemplo@gmail.com" value="{{ old('email') }}">
                            <span class="invalid-feedback" role="alert" id="emailError">
                                <strong></strong>
                            </span>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="passwordInput" class="form-label manager-name">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="passwordInput" required
                                autocomplete="new-password">
                            <span class="invalid-feedback" role="alert" id="passwordError">
                                <strong></strong>
                            </span>
                        </div>

                        <div class="col-12">
                            <label for="password_confirmationInput" class="form-label manager-confirm-pass">Confirmar Contraseña</label>
                            <input type="password" class="form-control" name="password_confirmation" required
                                id="password_confirmationInput" autocomplete="new-password">
                            <span class="invalid-feedback" role="alert" id="password_confirmationError">
                                <strong></strong>
                            </span>
                        </div>

                    </div>
                    <div class="modal-footer  justify-content-evenly mb-4">
                        <input type="submit" id="submit" class="save btn" value="Guardar" />
                        <input type="reset" class="cancel btn btn-secondary" data-bs-dismiss="modal" value="Cancelar" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- popup para editar administrador --}}
    <div class="modal fade modal-md" id="edit_manager" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="edit_manager_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-4">
                    <span class="icon-bordered fs-4" data-bs-dismiss="modal
                    <span class="icon-bordered fs-4" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-close"></i></span>
                    <h5 class="modal-title text-center w-100" id="edit_manager_label">Editar Administrador</h5>
                </div>
                <form id="edit_manager_form">
                    <div class="modal-body  mx-5 mb-4">
                        @csrf
                        <input type="hidden" name="edit_admin_id" id="edit_admin_id">
                        <div class="form-group">
                            <div class="col-12 mt-4 mb-3">
                                <label for="usernameEditInput" class="form-label manager-name">Nombre de Usuario</label>
                                <input type="text" class="form-control" name="username" id="usernameEditInput"
                                    required placeholder="Mohamed Shawabi" autofocus>
                                <span class="invalid-feedback" role="alert" id="usernameEditError">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="emailEditInput" class="form-label manager-email">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" id="emailEditInput" required
                                placeholder="ejemplo@gmail.com" value="{{ old('email') }}">
                            <span class="invalid-feedback" role="alert" id="emailEditError">
                                <strong></strong>
                            </span>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="passwordEditInput" class="form-label manager-name">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="passwordEditInput"
                                autocomplete="new-password" placeholder="Dejar en blanco si no desea cambiar">
                            <span class="invalid-feedback" role="alert" id="passwordEditError">
                                <strong></strong>
                            </span>
                        </div>

                        <div class="col-12">
                            <label for="password_confirmationEditInput" class="form-label manager-confirm-pass">Confirmar Contraseña Nueva</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmationEditInput" placeholder="Confirmar la nueva contraseña"
                                autocomplete="new-password">
                            <span class="invalid-feedback" role="alert" id="password_confirmationEditError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer  justify-content-evenly mb-4">
                        <input type="submit" id="submitEdit" class="save btn" value="Guardar" />
                        <input type="reset" class="cancel btn btn-secondary" data-bs-dismiss="modal" value="Cancelar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>


    {{-- popup de confirmación de eliminación --}}
    @component('components.delete-confirmation-modal', [
        'modalId' => 'delete_manager',
        'modalTitle' => 'Eliminar Administrador',
        'formAction' => '/deleteAdmin',
        'formInputName' => 'admin_id',
        'modalMessage' => '¿Está seguro de que desea eliminar?',
    ])
    @endcomponent

@endsection
