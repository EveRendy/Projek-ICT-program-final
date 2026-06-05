<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pengajuan Instalasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 700px;">
        <div class="card shadow border-0 p-4">
            <h4 class="mb-4">Form Pengajuan Instalasi Software</h4>

            @if($errors->has('software_error'))
                <div class="alert alert-danger py-2 small">{{ $errors->first('software_error') }}</div>
            @endif

            <form action="{{ route('pengajuan.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input type="text" name="mata_kuliah" class="form-control" required placeholder="Contoh: Web Programming">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelompok / Kelas</label>
                        <input type="text" name="kelompok_matkul" class="form-control" required placeholder="Contoh: LAB-A">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Laboratorium Tujuan</label>
                    <select name="laboratorium_id" id="laboratorium_id" class="form-select" required onchange="cekKompatibilitas()">
                        <option value="">-- Pilih Ruang Lab --</option>
                        @foreach($laboratoriums as $lab)
                            <option value="{{ $lab->id }}" data-level="{{ $lab->level }}">
                                {{ $lab->no_lab }} (Spesifikasi Level {{ $lab->level }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="text-muted my-4">

                <div class="card bg-white p-3 border mb-3">
                    <h6 class="text-primary mb-3">Opsi A: Pilih dari Daftar Software Master</h6>
                    <div class="mb-3">
                        <label class="form-label">Software</label>
                        <select name="software_id" id="software_id" class="form-select" onchange="updateVersiDanCek()">
                            <option value="">-- Pilih Software Terdaftar --</option>
                            @foreach($softwares as $soft)
                                <option value="{{ $soft->id }}" data-level="{{ $soft->keterangan }}" data-versi="{{ json_encode($soft->versi) }}">
                                    {{ $soft->nama_software }} (Butuh Spek Level {{ $soft->keterangan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Versi Software</label>
                        <select name="versi_requested" id="versi_requested" class="form-select">
                            <option value="">-- Pilih Versi --</option>
                        </select>
                    </div>
                </div>

                <div class="card bg-white p-3 border mb-3">
                    <h6 class="text-warning mb-3">Opsi B: Pengajuan Khusus (Jika tidak ada di daftar atas)</h6>
                    <div class="mb-3">
                        <label class="form-label">Nama Software Lain</label>
                        <input type="text" name="software_lain" class="form-control" placeholder="Contoh: CorelDraw X8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Versi Software Lain</label>
                        <input type="text" name="versi_lain" class="form-control" placeholder="Contoh: v24.0">
                    </div>
                </div>

                <div id="compatibility-warning" class="alert alert-warning py-2 d-none small">
                    ⚠️ <strong>Peringatan Kompatibilitas:</strong> Spesifikasi laboratorium tujuan (Level <span id="lab-lvl"></span>) lebih rendah dibandingkan level beban software (Level <span id="soft-lvl"></span>). Instalasi tetap dapat diajukan, namun kinerja software mungkin lambat.
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Kirim Pengajuan</button>
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary w-100 btn-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateVersiDanCek() {
            const softSelect = document.getElementById('software_id');
            const versiSelect = document.getElementById('versi_requested');
            const selectedOption = softSelect.options[softSelect.selectedIndex];

            // Reset dropdown versi
            versiSelect.innerHTML = '<option value="">-- Pilih Versi --</option>';

            if (selectedOption.value !== "") {
                // Ambil data versi JSON dari attribute option
                const daftarVersi = JSON.parse(selectedOption.getAttribute('data-versi'));
                daftarVersi.forEach(versi => {
                    let opt = document.createElement('option');
                    opt.value = versi;
                    opt.innerHTML = versi;
                    versiSelect.appendChild(opt);
                });
            }
            cekKompatibilitas();
        }

        function cekKompatibilitas() {
            const labSelect = document.getElementById('laboratorium_id');
            const softSelect = document.getElementById('software_id');
            const warningBox = document.getElementById('compatibility-warning');

            if (labSelect.value === "" || softSelect.value === "") {
                warningBox.classList.add('d-none');
                return;
            }

            const labLevel = parseInt(labSelect.options[labSelect.selectedIndex].getAttribute('data-level'));
            const softLevel = parseInt(softSelect.options[softSelect.selectedIndex].getAttribute('data-level'));

            // Aturan Bisnis 4: Jika level software lebih tinggi dari level lab, munculkan warning
            if (softLevel > labLevel) {
                document.getElementById('lab-lvl').innerText = labLevel;
                document.getElementById('soft-lvl').innerText = softLevel;
                warningBox.classList.remove('d-none');
            } else {
                warningBox.classList.add('d-none');
            }
        }
    </script>
</body>
</html>