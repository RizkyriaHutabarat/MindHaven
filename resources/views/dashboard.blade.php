<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MindHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Nunito:wght@400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
    @keyframes fadeInUp {
        from {
        opacity: 0;
        transform: translateY(20px);
        }
        to {
        opacity: 5;
        transform: translateY(0);
        }
    }
    .fade-in-up {
        animation: fadeInUp 5s ease-out;
    }
    .fade-in {
    animation: fadeIn 1.5s ease-out forwards;
    }

    @keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
    }

    .slide-in-left {
    animation: slideInLeft 1s ease-out forwards;
    }

    @keyframes slideInLeft {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
    }

    .slide-in-right {
    animation: slideInRight 1s ease-out forwards;
    }

    @keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
    }
    </style>
</head>
<body class="font-roboto">
        <nav class="bg-primary-200 py-2">
        <div class="container mx-auto flex justify-between items-center px-4">
        @if (session('user_token'))
    <<script>
        localStorage.setItem('user_token', '{{ session('user_token') }}');
    </script>
    @endif
            <div class="logo text-2xl font-bold text-gray-800">MindHaven</div>
            <div class="relative flex-1 max-w-sm hidden lg:block">
                <input class="pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 w-full" placeholder="Search plants..." type="text" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
            <div class="flex space-x-6 items-center">
                <a class="text-gray-800 font-semibold hover:text-green-600" href="#">Home</a>
                <!-- <a class="text-gray-800 hover:text-green-600" href="#">About</a> -->
                <a class="text-gray-800 hover:text-green-600" href="#services">Catalog</a>
                <!-- <a class="text-gray-800 hover:text-green-600" href="#testimonial">Reviews</a> -->
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.jadwal') }}">Jadwal</a>
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.riwayat') }}">Laporan</a>
                <a href="#" class="nav-link" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </div>
        </div>
        </nav>

        <div class="container mx-auto px-4 py-16 flex items-center fade-in">
      <!-- Text content -->
      <div class="w-1/2 text-content transform transition-all duration-1000 ease-out">
        <h1 class="text-4xl font-bold text-black mb-4 slide-in-left">
          Yuk, Mulai Perjalanan Kesehatan Mental Kamu Bersama
          <span class="text-blue-500">MindHaven!</span>
        </h1>
        <p class="text-gray-600 mb-8 slide-in-left">
          Kamu tidak harus menghadapi semuanya sendiri. Kami hadir untuk memberikan layanan konseling online dengan psikolog profesional dan berlisensi.
        </p>
        <div class="flex space-x-4 slide-in-left">
        <a href="#select-psikolog"
          class="bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-700 transition duration-300 block text-center">
          Konsultasi Sekarang
        </a>
          <!-- <button class="bg-gray-100 text-gray-600 px-6 py-2 rounded-full">Tes Gratis</button> -->
        </div>
      </div>

      <!-- Image section -->
      <div class="w-1/2 relative transform transition-all duration-1000 ease-out slide-in-right">
        <img alt="Illustration of a woman talking to a psychologist on a large smartphone screen" class="w-full" height="400" src="{{ asset('assets/images/5.jpg') }}" width="600"/>
      </div>
    </div>


    <section id="services" class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-blue-500 mb-2">Apa Yang Sedang Kamu Rasakan?</h1>
      <p class="text-gray-700 mb-6">Yuk, pilih perasaan yang sedang kamu hadapi dan temukan bantuan yang kamu butuhkan sekarang!</p>

      <!-- Feelings Grid -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Trauma')">
          <img src="{{ asset('assets/images/trauma.png') }}" alt="Illustration of a person experiencing trauma" class="mx-auto mb-2"/>
          <p class="text-blue-500 font-bold">Trauma</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Burnout')">
          <img src="{{ asset('assets/images/Burnout.png') }}" alt="Illustration of a person experiencing burnout" class="mx-auto mb-2"/>
          <p class="text-gray-700">Burnout</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Kecemasan')">
          <img src="{{ asset('assets/images/Kecemasan.png') }}" alt="Illustration of a person experiencing anxiety" class="mx-auto mb-2"/>
          <p class="text-gray-700">Kecemasan</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Stres')">
          <img src="{{ asset('assets/images/Stres.png') }}" alt="Illustration of a person experiencing stress" class="mx-auto mb-2"/>
          <p class="text-gray-700">Stres</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Depresi')">
          <img src="{{ asset('assets/images/Depresi.png') }}" alt="Illustration of a person experiencing depression" class="mx-auto mb-2"/>
          <p class="text-gray-700">Depresi</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Keluarga & hubungan')">
          <img src="{{ asset('assets/images/Keluarga & hubungan.png') }}" alt="Illustration of family and relationship issues" class="mx-auto mb-2"/>
          <p class="text-gray-700">Keluarga & hubungan</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Gangguan Mood')">
          <img src="{{ asset('assets/images/Gangguan Mood.png') }}" alt="Illustration of a person experiencing mood disorders" class="mx-auto mb-2"/>
          <p class="text-gray-700">Gangguan Mood</p>
        </div>
        <div class="feeling-item bg-white border rounded-lg p-4 text-center transform transition-transform duration-300 hover:scale-105 cursor-pointer" onclick="showFeelingInfo('Kecanduan')">
          <img src="{{ asset('assets/images/Kecanduan.png') }}" alt="Illustration of a person experiencing addiction" class="mx-auto mb-2"/>
          <p class="text-gray-700">Kecanduan</p>
        </div>
      </div>

      <!-- Selected feeling information
      <div id="feeling-info" class="bg-blue-100 p-6 rounded-lg hidden">
        <h2 id="feeling-title" class="text-blue-500 font-bold mb-2"></h2>
        <p id="feeling-description" class="text-gray-700 mb-4"></p>
        <div class="flex space-x-4">
          <button class="bg-green-500 text-white px-4 py-2 rounded-full">Tes Gratis</button>
          <button class="bg-blue-500 text-white px-4 py-2 rounded-full">Cari Psikolog</button>
        </div>
      </div> -->
    </section>

    <!-- <script>
      function showFeelingInfo(feeling) {
        let title = '';
        let description = '';

        // Set the title and description based on the clicked feeling
        if (feeling === 'Trauma') {
          title = 'Trauma: Luka Tak Kasat Mata yang Mempengaruhi Kesehatan Mental';
          description = 'Trauma adalah reaksi emosional yang intens dan berkepanjangan terhadap peristiwa yang sangat menegangkan atau menakutkan. Peristiwa tersebut bisa berupa kekerasan fisik atau seksual, kecelakaan, bencana alam, perang, atau penganiayaan.';
        } else if (feeling === 'Burnout') {
          title = 'Burnout: Keletihan Mental dan Fisik';
          description = 'Burnout adalah kondisi kelelahan fisik, emosional, dan mental yang disebabkan oleh stres yang berkepanjangan, biasanya akibat tekanan kerja yang berlebihan.';
        } else if (feeling === 'Kecemasan') {
          title = 'Kecemasan: Perasaan Takut yang Berlebihan';
          description = 'Kecemasan adalah perasaan takut atau khawatir yang berlebihan terhadap hal-hal yang tidak pasti, sering kali mengganggu kehidupan sehari-hari.';
        } else if (feeling === 'Stres') {
          title = 'Stres: Tekanan Hidup yang Berlebihan';
          description = 'Stres adalah respons tubuh terhadap situasi atau peristiwa yang dianggap menantang atau berbahaya, yang dapat memengaruhi kesehatan fisik dan mental.';
        } else if (feeling === 'Depresi') {
          title = 'Depresi: Gangguan Mood yang Menyebabkan Perasaan Sedih yang Mendalam';
          description = 'Depresi adalah gangguan mental yang menyebabkan perasaan sedih, kehilangan minat pada aktivitas yang biasanya menyenankan, dan dapat mempengaruhi kemampuan untuk berfungsi sehari-hari.';
        } else if (feeling === 'Keluarga & hubungan') {
          title = 'Masalah Keluarga & Hubungan: Membangun Komunikasi yang Sehat';
          description = 'Masalah keluarga atau hubungan sering kali terkait dengan komunikasi yang buruk, perbedaan nilai, atau ketidaksepahaman yang dapat menimbulkan stres atau konflik.';
        } else if (feeling === 'Gangguan Mood') {
          title = 'Gangguan Mood: Ketidakseimbangan Emosi yang Signifikan';
          description = 'Gangguan mood adalah kondisi mental yang menyebabkan perubahan suasana hati yang ekstrim, seperti dari perasaan sangat bahagia ke sangat sedih dalam waktu singkat.';
        } else if (feeling === 'Kecanduan') {
          title = 'Kecanduan: Ketergantungan yang Merusak Kehidupan';
          description = 'Kecanduan adalah kondisi di mana seseorang merasa tidak dapat mengendalikan perilaku tertentu, seperti penggunaan zat atau aktivitas, yang memengaruhi kesehatan fisik dan mental.';
        }

        // Display the feeling info section
        document.getElementById('feeling-title').textContent = title;
        document.getElementById('feeling-description').textContent = description;
        document.getElementById('feeling-info').classList.remove('hidden');
      }
    </script> -->


    <!-- Counter Section -->
    <section class="mb-9 py-5 bg-light">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <img class="mb-3 mx-auto" src="{{ asset('assets/images/Counter_1.png') }}" alt="Plants Delivered" style="max-width: 80%;" />
                <h1 class="text-secondary text-4xl font-bold">9,124,490</h1>
                <p class="text-green-600 font-bold">Plants Delivered</p>
            </div>
            <div class="text-center">
                <img class="mb-3 mx-auto" src="{{ asset('assets/images/Counter_2.png') }}" alt="Happy Customers" style="max-width: 80%;" />
                <h1 class="text-secondary text-4xl font-bold">36,487</h1>
                <p class="text-green-600 font-bold">Happy Customers</p>
            </div>
            <div class="text-center">
                <img class="mb-3 mx-auto" src="{{ asset('assets/images/Counter_3.png') }}" alt="Growing Catalog" style="max-width: 80%;" />
                <h1 class="text-secondary text-4xl font-bold">400+</h1>
                <p class="text-green-600 font-bold">Growing Catalog</p>
            </div>
        </div>
    </section>

    <!-- <section class="container mx-auto mt-8">
      <h1 class="text-center text-blue-500 text-3xl font-bold mb-8 fade-in-up">
        Pilih Paket Sekarang
      </h1>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($pakets as $paket)
          <div class="bg-blue-100 rounded-lg p-4 text-center flex flex-col justify-between h-full">
          <img alt="Package image" class="mx-auto mb-4" height="100"
          src="{{ asset('storage/' . $paket->gambar) }}" width="100"/>
            <h2 class="text-xl font-semibold">{{ $paket->nama_paket }}</h2>
            <p>{{ $paket->deskripsi }}</p>
            <p class="text-blue-500 font-bold mt-4">Rp{{ number_format($paket->harga, 0, ',', '.') }}</p>
            <a href="{{ route('booking') }}"
              class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 block text-center">
              Booking Sekarang
            </a>
          </div>
        @endforeach
      </div>
    </section> -->


    <section id="select-psikolog" class="container mx-auto mt-8">
  <h1 class="text-center text-blue-500 text-3xl font-bold mb-8 fade-in-up">
    Konsultasi Sekarang
  </h1>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    @foreach($psikologs as $psikolog)
      <div class="bg-white shadow-md hover:shadow-xl transition-transform transform hover:scale-105 rounded-lg p-6 text-center flex flex-col justify-between h-full">
        <img alt="Foto Psikolog" class="mx-auto mb-4 rounded-full border-4 border-blue-200"
        src="{{ asset('storage/' . $psikolog->foto) }}" width="100" height="100" />
        <h2 class="text-2xl font-bold text-gray-800">{{ $psikolog->nama }}</h2>
        <p class="text-blue-500 font-semibold mt-1">{{ $psikolog->spesialisasi }}</p>
        <p class="text-gray-600 mt-2 text-sm">{{ Str::limit($psikolog->bio, 1000) }}</p>
        <a href="{{ route('booking', ['psikolog_id' => $psikolog->id]) }}"
          class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg hover:from-blue-500 hover:to-blue-700 transition-colors block text-center font-medium">
          Pilih Psikolog
        </a>
      </div>
    @endforeach
  </div>
</section>



    <!-- <section class="container mx-auto mt-10 flex justify-between items-center fade-in slide-in">

        <div class="w-full sm:w-1/2 transform transition-all duration-700 ease-out">
            <img
                alt="Illustration of a person marking a date on a calendar"
                class="w-full h-auto slide-in-left"
                src="{{ asset('assets/images/4.jpg') }}"
            />
        </div> -->

        <!-- Right Section with Text and Button -->
        <!-- <div class="w-full sm:w-1/2 text-center mt-6 sm:mt-0 transform transition-all duration-700 ease-out slide-in-right">
        <h1 class="text-blue-500 text-2xl font-bold">
            Cara Booking Sesi Konsultasi
        </h1>
        <p class="mt-2 text-gray-600">
            Jangan tunggu lebih lama! Wujudkan ketenangan pikiranmu sekarang!
        </p>
        <button class="mt-4 px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
            <a href="{{ route('booking') }}" class="text-green-600 font-bold hover:text-green-800">
                Ayo Booking Sekarang!
            </a>
        </button>
      </div>
    </section> -->

  <!-- Cara melakukan Booking -->
    <section id="how-to-book" class="container mx-auto mt-8 px-4">
  <h1 class="text-3xl font-extrabold text-center text-green-600 mb-8">
    Cara Melakukan Booking di MindHaven
  </h1>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
    <!-- Image -->
    <div class="overflow-hidden rounded-lg shadow-lg">
      <img
        src="{{ asset('assets/images/4.jpg') }}"
        alt="Illustration of booking process"
        class="w-full h-auto object-cover transform transition-all duration-500 hover:scale-105"
      />
    </div>
    <!-- Steps -->
    <div class="space-y-6">
      <ol class="list-decimal list-inside text-gray-800 space-y-6 text-lg">
        <li>
          <strong class="text-green-600">Pilih Psikolog Konsultasi:</strong>
          Pilih salah satu Psikolog konsultasi yang sesuai dengan kebutuhan kamu dari daftar yang tersedia.
        </li>
        <li>
          <strong class="text-green-600">Klik "Pilih Psikolog":</strong>
          Setelah memilih psikolog, klik tombol <span class="text-blue-500 font-semibold">"Pilih Psikolog"</span>.
        </li>
        <li>
          <strong class="text-green-600">Isi Detail Pemesanan:</strong>
          Masukkan informasi yang diperlukan, seperti Tanggal Konsultasi, Waktu Konsultasi, Paket Konsultasi dan Jenis Konsultasi.
        </li>
        <li>
          <strong class="text-green-600">Konfirmasi dan Bayar:</strong>
          Periksa kembali detail pemesanan, lalu lakukan pembayaran melalui metode yang tersedia.
        </li>
        <li>
          <strong class="text-green-600">Tunggu Konfirmasi:</strong>
          Setelah pembayaran berhasil, kamu akan menerima nota pembelian yang berisi detail jadwal konsultasi.
        </li>
      </ol>
    </div>
  </div>
</section>

<!-- syarat dan ketentuan -->
<section id="terms-conditions" class="py-16 bg-white fade-in">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-extrabold mb-4 text-[#4A4A4A]">Syarat dan Ketentuan MindHaven</h2>
        <div class="flex flex-wrap mt-8 justify-center gap-6">
            <!-- Syarat 1 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Proses Booking</h5>
                <p class="text-[#4A4A4A]">Klien harus mengisi data lengkap dan memilih jadwal konsultasi sesuai dengan waktu yang tersedia.</p>
            </div>

            <!-- Syarat 2 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Pembatalan Konsultasi</h5>
                <p class="text-[#4A4A4A]">Jika klien membatalkan konsultasi dalam waktu kurang dari 24 jam sebelum jadwal, biaya yang sudah dibayarkan tidak dapat dikembalikan.</p>
            </div>

            <!-- Syarat 3 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Pengubahan Jadwal Konsultasi</h5>
                <p class="text-[#4A4A4A]">Pengubahan jadwal konsultasi dapat dilakukan maksimal 1 kali dengan pemberitahuan minimal 24 jam sebelumnya.</p>
            </div>

            <!-- Syarat 4 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Ketidakhadiran Klien</h5>
                <p class="text-[#4A4A4A]">Jika klien tidak hadir tanpa pemberitahuan dalam waktu 15 menit setelah jadwal konsultasi dimulai, konsultasi dianggap batal, dan biaya tidak dapat dikembalikan.</p>
            </div>

            <!-- Syarat 5 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Ketepatan Waktu Konsultasi</h5>
                <p class="text-[#4A4A4A]">Klien diminta untuk hadir tepat waktu sesuai dengan jadwal yang telah dipilih.</p>
            </div>

            <!-- Syarat 6 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Pengembalian Dana (Refund)</h5>
                <p class="text-[#4A4A4A]">Refund hanya diberikan jika pembatalan dilakukan oleh pihak penyedia layanan, dengan dana dikembalikan dalam waktu 7 hari kerja.</p>
            </div>

            <!-- Syarat 7 -->
            <div class="bg-[#D4EDDA] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#28A745]"></i>
                    </div>
                </div>
                <h5 class="font-semibold text-[#4A4A4A]">Kebijakan Tambahan</h5>
                <p class="text-[#4A4A4A]">Konsultasi hanya dapat dilakukan oleh klien yang telah melakukan pembayaran dan melengkapi semua persyaratan administrasi.</p>
            </div>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 bg-white fade-in">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-extrabold mb-4 text-[#4A4A4A]">Apa Kata Pengguna Kami?</h2>
            <h1 class="text-4xl font-bold text-green mt-2">Testimoni Dari <span class="text-green">Pengguna MindHaven</span></h1>
            <div class="flex flex-wrap mt-8 justify-center gap-6">
                <!-- Testimonial 1 -->
                <div class="bg-[#FFF3E0] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                        </div>
                    </div>
                    <p class="text-[#4A4A4A]">"MindHaven membantu saya menemukan kepercayaan diri kembali. Konsultasi yang ramah dan mudah diakses benar-benar membuat perbedaan."</p>
                    <div class="flex items-center mt-4">
                        <img alt="Profile picture of Sarah Lee" class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/women/5.jpg" />
                        <div>
                            <p class="font-bold text-[#4A4A4A]">Sarah Lee</p>
                            <p class="text-[#4A4A4A]">Mahasiswa</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-[#FFF3E0] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                        </div>
                    </div>
                    <p class="text-[#4A4A4A]">"Layanan konsultasi yang cepat dan profesional. Saya merasa didengar dan diberikan solusi yang nyata."</p>
                    <div class="flex items-center mt-4">
                        <img alt="Profile picture of David Brown" class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/men/7.jpg" />
                        <div>
                            <p class="font-bold text-[#4A4A4A]">David Brown</p>
                            <p class="text-[#4A4A4A]">Karyawan</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-[#FFF3E0] p-8 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-500 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                            <i class="fas fa-star text-[#F28C28]"></i>
                        </div>
                    </div>
                    <p class="text-[#4A4A4A]">"MindHaven membuat saya lebih memahami diri sendiri dan memberikan ruang untuk pertumbuhan mental yang positif."</p>
                    <div class="flex items-center mt-4">
                        <img alt="Profile picture of Maria Gonzalez" class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/women/6.jpg" />
                        <div>
                            <p class="font-bold text-[#4A4A4A]">Maria Gonzalez</p>
                            <p class="text-[#4A4A4A]">Ibu Rumah Tangga</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto">
            <!-- Upper Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <!-- About Us -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Tentang MindHaven</h3>
                    <p class="text-gray-400">
                        MindHaven adalah platform konsultasi psikolog online yang mendukung kesehatan mental Anda melalui layanan profesional, cepat, dan terpercaya.
                    </p>
                </div>
                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#about" class="text-gray-400 hover:text-[#F28C28]">Tentang Kami</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-[#F28C28]">Layanan</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-[#F28C28]">Testimoni</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-[#F28C28]">Kontak</a></li>
                    </ul>
                </div>
                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Hubungi Kami</h3>
                    <p class="text-gray-400">Email: <a href="mailto:support@MindHaven.com" class="hover:text-[#F28C28]">support@MindHaven.com</a></p>
                    <p class="text-gray-400">Telepon: <a href="tel:+6281234567890" class="hover:text-[#F28C28]">+62 812 3456 7890</a></p>
                    <div class="flex justify-center md:justify-start mt-4 space-x-4">
                        <a href="#" class="text-gray-400 hover:text-[#F28C28]"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-[#F28C28]"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-[#F28C28]"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-[#F28C28]"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <!-- Lower Section -->
            <div class="mt-8 border-t border-gray-600 pt-6 text-center">
                <p class="text-gray-400">&copy; 2024 MindHaven. Semua Hak Dilindungi.</p>
                <p class="text-gray-400 mt-2">Dibangun dengan ❤️ untuk kesehatan mental Anda.</p>
            </div>
        </div>
    </footer>

    <script>
        function handleLogout() {
    // Hapus token dari localStorage
    localStorage.removeItem('admin_token');

    // Submit form logout
    document.getElementById('logout-form').submit();
}
    </script>
</body>
</html>
