@extends('layouts.app')

@section('content')
<!-- inicio del título de la página -->
<div class="container-fluid stories-page" style="flex: 1 !important;">
    <div class="row text-center mt-4">
        <div class="title my-5">
            Panel de Control
        </div>
    </div>
</div>
<!-- fin del título de la página -->

<!-- inicio de la sección de estadísticas cuadradas -->
<div class="container">

    <div id="statistic" class="row justify-content-center gx-5 gy-2 mx-2 mb-4 my-2 mx-md-5 mx-sm-2">

        <!-- estadísticas uno para mostrar el total de usuarios y es el total de hombres y mujeres -->
        <div class="col-8 col-lg-4 col-md-6 col-sm-6 mb-2 mb-md-4">
            <div class="square-statistic shadow p-4">
                <div class="row">
                    <div class="col">
                        <span class="square-large-text count-animation" data-final-count="{{ $userCount->sum() }}">0</span>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <i class="fa-solid fa-person user-icon"></i>
                                <span class="count-animation" data-final-count="{{ $userCount->only([0, 1, 2])->sum() }}">0</span>
                            </div>
                            <div class="col">
                                <i class="fa-solid fa-person-dress user-icon"></i>
                                <span class="count-animation" data-final-count="{{ $userCount->only([3, 4, 5])->sum() }}">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="square-text">Número de Usuarios</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- estadísticas dos para mostrar el total de usuarios en cada nivel -->
        <div class="col-8 col-lg-4 col-md-6 col-sm-6 mb-2 mb-md-4">
            <div class="square-statistic shadow p-4">

                <!-- para mostrar el conteo de usuarios en el nivel uno -->
                <div class="row d-flex w-100">
                    <div class="col-8">
                        <span class="feature-level">
                            <span class="count-animation" data-final-count="{{ $userLevelCount->get(1, 0) }}">0</span>
                            Usuario
                        </span>
                    </div>
                    <div class="col-4">
                        <i class="fa-solid fa-1 level-icon"></i>
                    </div>
                </div>

                <!-- para mostrar el conteo de usuarios en el nivel dos -->
                <div class="row d-flex w-100">
                    <div class="col-8">
                        <span class="feature-level">
                            <span class="count-animation" data-final-count="{{ $userLevelCount->get(2, 0) }}">0</span>
                            Usuario
                        </span>
                    </div>
                    <div class="col-4">
                        <i class="fa-solid fa-2 level-icon"></i>
                    </div>
                </div>

                <!-- para mostrar el conteo de usuarios en el nivel tres -->
                <div class="row d-flex w-100">
                    <div class="col-8">
                        <span class="feature-level">
                            <span class="count-animation" data-final-count="{{ $userLevelCount->get(3, 0) }}">0</span>
                            Usuario
                        </span>
                    </div>
                    <div class="col-4">
                        <i class="fa-solid fa-3 level-icon"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <p class="square-text">Número de Usuarios por Nivel</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- estadísticas tres para mostrar el total de administradores -->
        <div class="col-8 col-lg-4 col-md-6 col-sm-6 mb-2 mb-md-4">
            <div class="square-statistic shadow p-4">

                <div class="row d-flex w-100">
                    <div class="col-8 text-center">
                        <span class="square-large-text count-animation" data-final-count="{{ $adminCount }}">0</span>
                    </div>

                    <div class="col-4 ml-auto">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <p class="square-text">Número de Administradores</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- fin de la sección de estadísticas cuadradas -->
<!-- inicio de la sección de estadísticas circulares -->
<div class="container">
    <div id="round-total" class="row justify-content-center">

        <!-- título de estadísticas circulares -->
        <div class="stories-statics-title text-center my-5">
            Número de Historias por Nivel
        </div>

        <!-- Bucle a través de cada nivel y mostrar el conteo -->
        @for ($level = 1; $level <= 3; $level++) 
        <?php
            // para convertir el número de nivel de historia a texto como: 3 => difícil
            $level_type = ($level == 1) ? 'Fácil' : ($level == 2 ? 'Medio' : 'Difícil');
        ?>
            
            <!-- Verificar si el nivel existe en $levelCounts -->
            @if ($levelCounts->has($level))
            <?php $count = $levelCounts[$level]; ?>

            <!-- Mostrar la caja de nivel con el conteo de historias en ella -->
            <div class="col-md-4 col-sm-6 d-flex align-items-center justify-content-center round-box-container">
                <div class="round-box mb-2 p-4 ">
                    <div class="row">
                        <div class="col text-center">
                            <p class="count-animation" data-final-count="{{ $count }}">0</p>
                            <p>{{ $level_type }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Mostrar la caja de nivel con conteo 0 -->
            <div class="col-md-4 col-sm-6 d-flex align-items-center justify-content-center">
                <div class="round-box mb-2 p-4 ">
                    <div class="row">
                        <div class="col text-center">
                            <p>0</p>
                            <p>{{ $level_type }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        @endfor

    </div>
</div>
<!-- fin de la sección de estadísticas circulares -->


<!-- inicio de la sección del gráfico de líneas -->

<!-- importar script de Google Chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- pasar datos de crecimiento de usuarios a chart.js -->
<script>
    var userGrowthData = @json($userGrowth);
</script>

<!-- cargar el archivo chart.js -->
<script type="text/javascript" src="{{ asset('js/chart.js') }}"></script>


<!-- sección de diseño del gráfico de líneas -->
<div class="container-fluid" id="chart_container">
    <div class="row my-4">
        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <img src="{{ asset('storage/img/person_two.png') }}" class=" img-fluid chart-img" alt="">
        </div>
        <div class="col-lg-6 col-md-4 col-12 d-flex justify-content-center m-0 p-0">
            <div id="curve_chart" style="width: 900px; height: 500px;"></div>
        </div>
        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <img src="{{ asset('storage/img/person_one.png') }}" class=" img-fluid chart-img" alt="">
        </div>
    </div>
</div>

<!-- fin de la sección del gráfico de líneas -->


@endsection