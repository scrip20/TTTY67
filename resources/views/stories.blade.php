@extends('layouts.app')

@section('content')
    <div class="container-fluid stories-page">

        <!-- start of loading overlay element -->
        <div id="loading-overlay">
            <div class="spinner"></div>
        </div>
        <!-- end of loading overlay element -->

        <div class="row text-center mt-4">
            <div class="title">
                Historias
            </div>
        </div>
        <!-- end of page title section -->

        <!-- start of story search section -->
        <div class="row mt-4 d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <form method="get" action="/stories/{{ $level }}/">
                    <div class="search form-group">
                        <span class="search-icon icon-bordered">
                            <i class="fa-solid fa-magnifying-glass fa-flip-horizontal" id="search_icon"
                                style="--fa-animation-duration: 1s;"></i>
                        </span>
                        <input type="text" value="{{ $search }}" name="search" id="search_txt"
                            oninput="performSearch('/stories', { search: this.value.trim(), level: '{{ $level }}' })"
                            autocomplete="off" class="form-control shadow-none" placeholder="Buscar una historia...">
                        <input type="submit" id="search_btn" value="Buscar" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <!-- search result list  -->
            <div class="col-md-9">
                <div class="list-group" id="result_list"></div>
            </div>
        </div>
        <!-- end of story search section -->

        <!-- start of stories level section  -->
        <div class="row mt-4 ">
            <div class="level-tap">
                <ul class="nav nav-pills text-center justify-content-center">
                    <li class="nav-item col-lg-2 col-md-3 {{ $level == 1 ? 'active' : '' }} " value="1">
                        <a class="nav-link" href="/stories/1">Fácil</a>
                    </li>
                    <li class="nav-item col-lg-2 col-md-3 {{ $level == 2 ? 'active' : '' }}" value="2">
                        <a class="nav-link" href="/stories/2">Medio</a>
                    </li>
                    <li class="nav-item col-lg-2 col-md-3 {{ $level == 3 ? 'active' : '' }} " value="3">
                        <a class="nav-link" href="/stories/3">Difícil</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end of stories level section   -->

        <!-- start of stories card section -->
        <div class="row mt-4 justify-content-center">

            <div class="cards text-center" id="cards_container">

                <!-- check if there is a story -->
                @if (count($stories) > 0)
                    <!-- display all story -->
                    @foreach ($stories as $story)
                        <div class="out-card m-2">
                            <div class="card card-story">

                                <img src="{{ asset('storage/upload/stories_covers/' . $story->cover_photo) }}"
                                    class="img-fluid rounded " alt="..." />
                                <a href="/slide/{{ $story->id }}" title="{{ $story->name }}" class="hover-background"></a>

                                <!-- if story is not published then show the icons -->
                                @if ($story->published == 0)
                                    <ul class="story-links">

                                        <!-- show publish icon only for admin  -->
                                        @if (Auth::user()->role == 'admin')

                                            <li>
                                                <!-- check if story has media then show publish pop-up else show no publish pop-up  -->
                                                @if ($story->hasMedia)
                                                    <a class="shadow" id="publish_icon"
                                                        onclick="deletePopup({{ $story->id }},'publish_story','publish_story_id')"
                                                        data-tip="Publicar la historia"><i class="fa fa-bullhorn"></i></a>
                                                @else
                                                    <a class="shadow" id="publish_icon" data-bs-toggle="modal"
                                                        data-bs-target="#no_pub_pop" data-tip="Publicar la historia"><i
                                                            class="fa fa-bullhorn"></i></a>
                                                @endif
                                            </li>
                                        @endif

                                        <!-- delete icon -->
                                        <li>
                                            <a class="shadow" id="delete_icon"
                                                onclick="deletePopup({{ $story->id }},'delete_story','story_id')"
                                               
                                                data-tip="Eliminar la historia">
                                                <i class="fa fa-trash-can"></i>
                                            </a>
                                        </li>

                                        <!-- edit story icon -->
                                        <li>
                                            <a class="shadow" id="edit_icon"
                                                onclick='editStory({{ $story->id }},"{{ $story->name }}" , "{{ $story->author }}" , "{{ $story->cover_photo }}" , "{{ $story->story_order }}" , {{ $level }})'
                                                data-tip="Editar la historia">
                                                <i class="fa fa-pen"></i>
                                            </a>
                                        </li>

                                    </ul>
                                @else
                                    <!-- show the published tag in the story card -->
                                    <span class="publish-tag">
                                        <span>
                                            Publicado
                                            <small>
                                                <a id="publish_icon"><i class="fa fa-bullhorn"></i></a>
                                            </small>
                                        </span>
                                    </span>
                                @endif

                                <!-- show story name and author and limit to specific character -->
                                <div class="card-story-body">
                                    <h4 class="card-story-title text-center pt-3 ">
                                        {{ \Illuminate\Support\Str::limit($story->name, 30) }}
                                    </h4>
                                    <h6 class="card-story-text text-center pb-2">
                                        {{ \Illuminate\Support\Str::limit($story->author, 18) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- display a message if there is no story  -->
                    <div class="container text-center">

                        @if (request()->route()->getName() === 'stories' && !request()->has('search'))
                            <h1 class="mb-0 pb-0 pt-5 text-muted">¡No hay historias hasta ahora!</h1>
                        @elseif(request()->route()->getName() === 'stories' && request()->has('search'))
                            <h1 class="mb-0 pb-0 pt-5 text-muted">¡No hay una historia con ese nombre!</h1>
                        @endif

                        <img src="{{ asset('storage/upload/No_data.svg') }}" class="img-fluid w-75 w">
                    </div>
                @endif
            </div>
        </div>
        <!-- end of stories card section  -->

        <!-- add story btn -->
        <a class="btn overflow-visible add-btn text-white shadow" href="#" role="button" data-bs-toggle="modal"
            data-bs-target="#add_story">
            <i class="fa fa-add"></i>
            <span style="display: none;">
                Añadir historia
            </span>
        </a>

        {{-- add_story popup --}}
        <div class="modal fade modal-md" id="add_story" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="add_story_label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-4">
                        <span class="icon-bordered fs-4" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-close"></i></span>
                        <h5 class="modal-title text-center w-100" id="add_story_label">Añadir historia</h5>
                    </div>
                    <form id="story_form" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body mx-5 mb-4">
                            <input type="hidden" name="level" id="level" value="{{ $level }}">
                            <div class="form-group">
                                <div class="col-12 mt-4 mb-3">
                                    <label for="cover_photo" class="form-label cover-photo">Imagen de la historia</label>
                                    <div class="line-vertical"></div>
<label class="form-control text-truncate" id="cover_photoLabel"
    for="cover_photoInput">
    Selecciona una imagen para subir
    <span class="icon-bordered upload-icon"><i class="fa fa-upload"></i></span>
</label>

                                    <input type="file" onchange="updateLabelName('#cover_photoLabel', this)"
                                        class="d-none" name="cover_photo" id="cover_photoInput" accept="image/*">
                                    <span class="invalid-feedback" role="alert" id="cover_photoError">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label story-title">Título de la historia</label>
                                <input required type="text" class="form-control" name="story_name"
                                   
                                id="story_nameInput" placeholder="Caperucita Roja">
                                <span class="invalid-feedback" role="alert" id="story_nameError">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="author" class="form-label story-author">Nombre del autor</label>
                                <input required type="text" class="form-control" name="author" id="authorInput"
                                    placeholder="Juan Pérez">
                                <span class="invalid-feedback" role="alert" id="authorError">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="story_order" class="form-label order">Orden de la historia en el nivel</label>
                                <input required type="number" class="form-control" name="story_order"
                                    id="story_orderInput" data-order="0"
                                    oninput="checkLastOrder(this ,'#warning_order' )">
                                <span class="invalid-feedback" role="alert" id="story_orderError">
                                    <strong></strong>
                                </span>
                                <div class="warning-order mt-4 alert-warning rounded" id="warning_order"></div>
                            </div>
                        </div>
                        <div class="modal-footer  justify-content-evenly mb-4">
                            <input type="submit" class="save btn" value="Guardar" />
                            <input type="reset" class="cancel btn btn-secondary" data-bs-dismiss="modal"
                                value="Cancelar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- edit_story popup --}}
        <div class="modal fade modal-md" id="edit_story" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="add_story_label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-4">
                        <span class="icon-bordered fs-4" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-close"></i>
                        </span>
                        <h5 class="modal-title text-center w-100" id="edit_story_label">Editar historia</h5>
                    </div>
                    <form id="edit_story_form" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body mx-5 mb-4">
                            <div class="form-group">
                                <label for="edit_level" class="form-label edit_level">Nivel de la historia</label>
                                <select name="level" id="edit_level" class="form-control">
                                    <option value="1">Fácil</option>
                                    <option value="2">Medio</option>
                                    <option value="3">Difícil</option>
                                </select>
                            </div>
                            <input type="hidden" name="edit_story_id" id="edit_story_id">
                            <div class="form-group">
                                <div class="col-12 mt-4 mb-3">
                                    <label for="cover_photo" class="form-label cover-photo">Imagen de la historia</label>
                                    <div class="line-vertical"></div>
                                    <label class="form-control text-truncate" id="cover_photoEditLabel"
                                        for="cover_photoEditInput">
                                        Selecciona una imagen para subir
                                        <span class="icon-bordered upload-icon"><i class="fa fa-upload"></i></span>
                                    </label>
                                    <input type="file" onchange="updateLabelName('#cover_photoEditLabel', this)"
                                        class="d-none" name="cover_photo" id="cover_photoEditInput"
                                        accept="image/*">
                                    <span class="invalid-feedback" role="alert" id="cover_photoEditError">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="story_name" class="form-label story-title">Título de la historia</label>
                                <input type="text" class="form-control" name="story_name" id="story_nameEditInput"
                                    required placeholder="Caperucita Roja">
                                <span class="invalid-feedback" role="alert" id="story_nameEditError">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="author" class="form-label story-author">Nombre del autor</label>
                                <input type="text" class="form-control" name="author" id="authorEditInput" required
                                    placeholder="Juan Pérez">
                                <
                                <span class="invalid-feedback" role="alert" id="authorEditError">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="story_order" class="form-label order">Orden de la historia en el nivel</label>
                                <input type="number" class="form-control" name="story_order" id="story_orderEditInput"
                                    required data-order="0" oninput="checkLastOrder(this ,'#warning_edit_order' )">
                                <span class="invalid-feedback" role="alert" id="story_orderEditError">
                                    <strong></strong>
                                </span>
                                <div class="warning-order mt-4 alert-warning rounded" id="warning_edit_order"></div>
                            </div>
                        </div>
                        <div class="modal-footer  justify-content-evenly mb-4">
                            <input type="submit" id="submit" class="save btn" value="Guardar" />
                            <input type="reset" class="cancel btn btn-secondary" data-bs-dismiss="modal"
                                value="Cancelar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- publish popup --}}
        <div class="modal fade" tabindex="-1" id="publish_story" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Publicar historia</h5>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="/publishStory" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="story_id" id="publish_story_id">
                            <p class="text-center delete-text">¿Estás seguro de que quieres publicar esta historia?</p>
                        </div>
                        <div class="modal-footer justify-content-evenly">
                            <button type="submit" class="btn save">Publicar</button>
                            <button type="button" class="btn btn-secondary cancel"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- no publish popup --}}
        <div class="modal fade" tabindex="-1" id="no_pub_pop" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">¡Lo siento!</h4>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center delete-text">Lo siento, no se puede publicar una historia vacía.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- delete popup --}}
        @component('components.delete-confirmation-modal', [
            'modalId' => 'delete_story',
            'modalTitle' => 'Eliminar historia',
            'formAction' => '/deleteStory',
            'formInputName' => 'story_id',
            'modalMessage' => '¿Estás seguro de que quieres eliminar esta historia?',
        ])
        @endcomponent

    </div>

@endsection
