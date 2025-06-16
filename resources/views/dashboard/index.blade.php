@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Ringkasan Arus Kas Bulan Ini</h3>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6 class="text-success">Total Kas Masuk</h6>
                    <h3>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-danger shadow-sm">
                <div class="card-body">
                    <h6 class="text-danger">Total Kas Keluar</h6>
                    <h3>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h6 class="text-primary">Saldo Akhir</h6>
                    <h3>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Grafik Saldo Kas per Bulan</h5>
            <canvas id="kasChart" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kasChart').getContext('2d');

    const kasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [{
                label: 'Saldo Kas',
                data: {!! json_encode(array_column($monthlyData, 'saldo')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
