@extends('layouts.app')

@section('content')
    <h1>Historial de Pedidos</h1>
    @forelse ($orders as $order)
        <div>
            <p>Pedido ID: {{ $order->id }}</p>
            <p>Fecha: {{ $order->created_at->toDateString() }}</p>
            <p>Estado: {{ $order->status }}</p>
            <!-- Agrega más detalles según sea necesario -->
        </div>
    @empty
        <p>No tienes pedidos anteriores.</p>
    @endforelse
@endsection