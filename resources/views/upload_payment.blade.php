<form action="{{ route('payment.store', $jadwalkonsul->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="bukti_pembayaran" class="form-label fw-bold">Bukti Pembayaran</label>
        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control shadow-sm" required>
    </div>
    
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
Ini akan menampil

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg shadow-sm">Upload Pembayaran</button>
    </div>
</form>
