@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h4 class="fw-bold">Edit Jadwal Konsultasi</h4>
    <form action="{{ route('admin.update_jadwalkonsul', $jadwalKonsul->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="id_psikologs">Nama Psikolog</label>
            <select name="id_psikologs" id="id_psikologs" class="form-control" required>
                @foreach($psikologs as $psikolog)
                    <option value="{{ $psikolog->id }}" {{ $psikolog->id == $jadwalKonsul->id_psikologs ? 'selected' : '' }}>{{ $psikolog->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="id_pakets">Paket Konsultasi</label>
            <select name="id_pakets" id="id_pakets" class="form-control" required>
                @foreach($pakets as $paket)
                    <option value="{{ $paket->id }}" {{ $paket->id == $jadwalKonsul->id_pakets ? 'selected' : '' }}>{{ $paket->nama_paket }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="jam">Jam Konsultasi</label>
            <input type="time" name="jam" class="form-control" value="{{ $jadwalKonsul->jam }}" required>
        </div>
        <!-- <div class="form-group mb-3">
            <label for="hari">Hari</label>
            <input type="text" name="hari" class="form-control" value="{{ $jadwalKonsul->hari }}" required>
        </div> -->
        <!-- Tanggal Konsultasi -->
        <div class="mb-4">
                <label for="tanggal" class="form-label fw-bold">Tanggal Konsultasi</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control shadow-sm" value="{{ \Carbon\Carbon::parse($jadwalKonsul->tanggal)->format('Y-m-d') }}" >
                <input type="hidden" name="hari" id="hari" value="{{ $jadwalKonsul->hari }}"> <!-- Hidden field for day -->
        </div>
        <div class="form-group mb-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required>{{ $jadwalKonsul->deskripsi }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label for="metodepembayaran">Metode Pembayaran</label>
            <input type="text" name="metodepembayaran" class="form-control" value="{{ $jadwalKonsul->metodepembayaran }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Jadwal</button>
    </form>
</div>


<script>
  // JavaScript to handle time slot selection
  const timeSlots = document.querySelectorAll('.time-slot-btn');
  const selectedTimeText = document.getElementById('selected-time');
  const submitButton = document.getElementById('submit-time');
  const timeInput = document.getElementById('jam'); // Hidden time input

  timeSlots.forEach(button => {
    button.addEventListener('click', () => {
      const selectedTime = button.getAttribute('data-time');
      selectedTimeText.textContent = `Waktu yang dipilih: ${selectedTime}`;
      
      // Set the hidden input with the selected time
      timeInput.value = selectedTime;

      // Enable submit button
      submitButton.disabled = false;
    });
  });

// Menambahkan event listener untuk memperbarui hari setelah memilih tanggal
document.getElementById('tanggal').addEventListener('change', formatTanggal);

function formatTanggal() {
    const tanggalInput = document.getElementById('tanggal').value; // Ambil input tanggal
    const hariInput = document.getElementById('hari'); // Hidden input untuk hari

    if (tanggalInput) {
        const tanggal = new Date(tanggalInput);
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };

        const formattedDate = tanggal.toLocaleDateString('id-ID', options); // Format menjadi Hari, Tanggal Bulan Tahun
        hariInput.value = formattedDate; // Set nilai ke hidden input
    } else {
        alert("Pilih tanggal konsultasi terlebih dahulu.");
        return false;
    }

    return true;
}

</script>
@endsection
