<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPS - {{ $rps->nama ?? 'Rencana Pembelajaran Semester' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 15px;
            line-height: 1.4;
            font-size: 10pt;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #000;
            padding-bottom: 12px;
            display: table;
            width: 100%;
        }
        .header-logo {
            display: table-cell;
            width: 15%;
            text-align: center;
            vertical-align: middle;
            padding-right: 10px;
        }
        .header-logo img {
            max-width: 80px;
            max-height: 80px;
        }
        .header-content {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }
        .header-qr {
            display: table-cell;
            width: 15%;
            text-align: center;
            vertical-align: middle;
            padding-left: 10px;
        }
        .header-qr img {
            max-width: 80px;
            max-height: 80px;
            border: 1px solid #999;
        }
        .university {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .title {
            font-size: 13pt;
            font-weight: bold;
            margin: 8px 0 5px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 9pt;
        }
        .info-table th, .info-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        .info-table th {
            background-color: #e6e6e6;
            font-weight: bold;
            width: 20%;
        }
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .section-title {
            background: #d9d9d9;
            padding: 5px 8px;
            font-weight: bold;
            border: 1px solid #000;
            margin-bottom: 6px;
            font-size: 10pt;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8.5pt;
        }
        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: left;
            vertical-align: top;
        }
        .content-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .page-break { page-break-after: always; }
        .signature-section {
            margin-top: 35px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            text-align: center;
            width: 50%;
            padding: 0 15px;
            font-size: 9pt;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 3px;
            min-height: 15px;
        }
        .otorisasi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .otorisasi-table td {
            text-align: center;
            padding: 4px;
            width: 33%;
            font-size: 9pt;
            border: none;
        }
        .otorisasi-label {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .otorisasi-name {
            border-top: 1px solid #000;
            padding-top: 20px;
            min-height: 15px;
        }
        .lecture-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .lecture-list li {
            margin-bottom: 2px;
            font-size: 9pt;
        }
        .description-text {
            font-size: 9pt;
            line-height: 1.5;
            text-align: justify;
        }
        .table-note {
            font-size: 8pt;
            margin: 3px 0;
            font-style: italic;
        }
        .small-text {
            font-size: 8pt;
        }
        ol {
            margin-left: 20px;
            font-size: 9pt;
        }
        ol li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <!-- ===== HEADER ===== -->
    <div class="header">
        <div class="header-logo">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="University Logo">
            @else
                <div style="font-size: 10pt; font-weight: bold;">LOGO</div>
            @endif
        </div>
        <div class="header-content">
            <div class="university">{{ strtoupper($university->name ?? 'MNC UNIVERSITY') }}</div>
            <div class="university" style="font-size: 10pt; margin-top: 3px;">{{ strtoupper($rps->faculty->name ?? 'FAKULTAS') }} - {{ strtoupper($rps->studyProgram->name ?? 'PROGRAM STUDI') }}</div>
            <div class="title">RENCANA PEMBELAJARAN SEMESTER</div>
        </div>
        <div class="header-qr">
            @if($qrCodePath && file_exists($qrCodePath))
                <img src="{{ $qrCodePath }}" alt="QR Code">
                <div style="font-size: 7pt; margin-top: 3px;">Verify RPS</div>
            @endif
        </div>
    </div>

    <!-- ===== INFORMASI MATA KULIAH ===== -->
    <table class="info-table">
        <tr>
            <th>Mata Kuliah (MK)</th>
            <td colspan="3"><strong>{{ $rps->course->name ?? '-' }}</strong></td>
        </tr>
        <tr>
            <th>Kode</th>
            <td><strong>{{ $rps->course->code ?? '-' }}</strong></td>
        <tr>
            <th>Rumpun MK</th>
            <td>{{ $rps->course->category ?? '-' }}</td>
        </tr>
        <tr>
            <th>Bobot (SKS)</th>
            <td>{{ $rps->course->credits ?? '-' }}</td>
            <th>Semester</th>
            <td>{{ $rps->semester ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Penyusunan</th>
            <td colspan="3">{{ $rps->created_at ? $rps->created_at->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <th>Otorisasi</th>
            <td colspan="3">
                <table class="otorisasi-table">
                    <tr>
                        <td>
                            <div class="otorisasi-label">Pengembang RPS</div>
                            <div class="otorisasi-name">{{ $rps->lecturer->name ?? 'Belum Ditentukan' }}</div>
                        </td>
                        <td>
                            <div class="otorisasi-label">Koordinator RPS</div>
                            <div class="otorisasi-name">{{ $rps->coordinator->name ?? 'Belum Ditentukan' }}</div>
                        </td>
                        <td>
                            <div class="otorisasi-label">Ketua Prodi</div>
                            <div class="otorisasi-name">{{ $rps->headOfProgram->name ?? 'Belum Ditentukan' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ===== CAPAIAN PEMBELAJARAN ===== -->
    <div class="section">
        <div class="section-title">A. CAPAIAN PEMBELAJARAN (CP)</div>

        <div style="margin: 8px 0 4px 0; font-weight: bold; font-size: 9pt;">CPL-PRODI yang Dibebankan pada Mata Kuliah</div>

        <table class="content-table">
            <tr>
                <th width="8%">Kode</th>
                <th width="92%">Deskripsi</th>
            </tr>
            @php
                $ploMappedCodes = is_string($rps->plo_mapped) ? json_decode($rps->plo_mapped, true) : ($rps->plo_mapped ?? []);
                $ploMapped = [];
                if ($ploMappedCodes && is_array($ploMappedCodes)) {
                    $ploMapped = \App\Models\ProgramLearningOutcome::whereIn('code', $ploMappedCodes)->orderBy('code')->get()->toArray();
                }
            @endphp
            @forelse($ploMapped as $plo)
            <tr>
                <td class="text-center">{{ $plo['code'] ?? '-' }}</td>
                <td>{{ $plo['description'] ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center small-text"><em>Belum ada CPL-PRODI yang ditambahkan</em></td>
            </tr>
            @endforelse
        </table>

        <div style="margin: 10px 0 4px 0; font-weight: bold; font-size: 9pt;">Capaian Pembelajaran Mata Kuliah (CPMK)</div>

        <table class="content-table">
            <tr>
                <th width="10%">Kode</th>
                <th width="90%">Deskripsi</th>
            </tr>
            @php
                $cloListCodes = is_string($rps->clo_list) ? json_decode($rps->clo_list, true) : ($rps->clo_list ?? []);
                $cloList = [];
                if ($cloListCodes && is_array($cloListCodes)) {
                    $cloList = \App\Models\CourseLearningOutcome::whereIn('code', $cloListCodes)->orderBy('code')->get()->toArray();
                }
            @endphp
            @forelse($cloList as $clo)
            <tr>
                <td class="text-center">{{ $clo['code'] ?? '-' }}</td>
                <td>{{ $clo['description'] ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center small-text"><em>Belum ada CPMK yang ditambahkan</em></td>
            </tr>
            @endforelse
        </table>

        <div style="margin: 10px 0 4px 0; font-weight: bold; font-size: 9pt;">Kemampuan Akhir Tiap Tahapan Belajar (Sub-CPMK)</div>

        <table class="content-table">
            <tr>
                <th width="10%">Kode</th>
                <th width="90%">Deskripsi</th>
            </tr>
            @php
                $studyFieldCodes = is_string($rps->study_field_mapped) ? json_decode($rps->study_field_mapped, true) : ($rps->study_field_mapped ?? []);
                $studyFields = [];
                if ($studyFieldCodes && is_array($studyFieldCodes)) {
                    $studyFields = \App\Models\StudyField::whereIn('code', $studyFieldCodes)->orderBy('code')->get()->toArray();
                }
            @endphp
            @forelse($studyFields as $field)
            <tr>
                <td class="text-center">{{ $field['code'] ?? '-' }}</td>
                <td>{{ $field['description'] ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center small-text"><em>Belum ada Sub-CPMK yang ditambahkan</em></td>
            </tr>
            @endforelse
        </table>
    </div>

    <!-- ===== DESKRIPSI SINGKAT ===== -->
    <div class="section">
        <div class="section-title">B. DESKRIPSI SINGKAT</div>
        <p class="description-text">{{ $rps->course_description ?? '-' }}</p>
    </div>

    <!-- ===== DOSEN PENGAMPU ===== -->
    <div class="section">
        <div class="section-title">C. DOSEN PENGAMPU</div>
        @if ($rps->lecturer)
        <ul class="lecture-list">
            <li>• {{ $rps->lecturer->name }}</li>
        </ul>
        @else
        <p class="table-note">Belum ada Dosen Pengampu yang ditugaskan</p>
        @endif
    </div>

    <!-- ===== MATA KULIAH SYARAT ===== -->
    <div class="section">
        <div class="section-title">D. MATA KULIAH SYARAT</div>
        <p style="font-size: 9pt;">{{ $rps->prerequisites ?? '-' }}</p>
    </div>

    <!-- ===== DAFTAR REFERENSI ===== -->
    <div class="section">
        <div class="section-title">E. DAFTAR REFERENSI</div>
        @php
            $mainRefs = is_string($rps->main_references) ? json_decode($rps->main_references, true) : ($rps->main_references ?? []);
            $supportRefs = is_string($rps->supporting_references) ? json_decode($rps->supporting_references, true) : ($rps->supporting_references ?? []);
            $allRefs = array_merge($mainRefs ?: [], $supportRefs ?: []);
        @endphp
        @if (!empty($allRefs))
        <ol>
            @foreach($allRefs as $ref)
            <li>
                {{ $ref['author'] ?? 'Anonim' }}
                @if($ref['year'] ?? null) ({{ $ref['year'] }}) @endif
                <strong>{{ $ref['title'] ?? 'Tanpa Judul' }}</strong>.
                {{ $ref['publisher'] ?? 'Tanpa Penerbit' }}.
            </li>
            @endforeach
        </ol>
        @else
        <p class="table-note">Belum ada referensi yang ditambahkan</p>
        @endif
    </div>

    <!-- ===== RENCANA PEMBELAJARAN MINGGUAN ===== -->
    <div class="section">
        <div class="section-title">F. RENCANA PEMBELAJARAN MINGGUAN</div>

        @php
            $weeklyPlan = is_string($rps->weekly_plan) ? json_decode($rps->weekly_plan, true) : ($rps->weekly_plan ?? []);
        @endphp

        @if (!empty($weeklyPlan))
        <table class="content-table">
            <thead>
                <tr>
                    <th rowspan="2" width="4%">Mg</th>
                    <th rowspan="2" width="12%">Topik/Materi</th>
                    <th rowspan="2" width="15%">Sub-CPMK</th>
                    <th colspan="2" width="18%">Penilaian</th>
                    <th rowspan="2" width="12%">Metode</th>
                    <th rowspan="2" width="12%">Tugas Mahasiswa</th>
                    <th rowspan="2" width="10%">Referensi</th>
                    <th rowspan="2" width="5%">Bobot %</th>
                </tr>
                <tr>
                    <th width="9%">Indikator</th>
                    <th width="9%">Bentuk</th>
                </tr>
            </thead>
            <tbody>
                @foreach($weeklyPlan as $week)
                <tr>
                    <td class="text-center">{{ $week['week'] ?? $loop->iteration }}</td>
                    <td>{{ $week['topic'] ?? '-' }}</td>
                    <td>{{ $week['learning_outcomes'] ?? '-' }}</td>
                    <td>
                        @if(isset($week['indicator_ik']) && !empty($week['indicator_ik']))
                            @php
                                $ikIds = is_array($week['indicator_ik']) ? $week['indicator_ik'] : [$week['indicator_ik']];
                                $indicators = \App\Models\LearningOutcomeIndicator::whereIn('id', $ikIds)->pluck('description')->toArray();
                            @endphp
                            @foreach($indicators as $ik)
                                • {{ $ik }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $assessmentMethod = $week['assessment_method'] ?? [];
                            if (is_array($assessmentMethod) && count($assessmentMethod) > 0) {
                                echo implode(', ', $assessmentMethod);
                            } else {
                                echo '-';
                            }
                        @endphp
                    </td>
                    <td>
                        @php
                            $method = $week['method'] ?? [];
                            if (is_array($method) && count($method) > 0) {
                                echo implode(', ', $method);
                            } else {
                                echo '-';
                            }
                        @endphp
                    </td>
                    <td>{{ $week['student_task'] ?? '-' }}</td>
                    <td>{{ $week['references'] ?? '-' }}</td>
                    <td class="text-center">{{ $week['bobot'] ?? '-' }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="table-note">Belum ada Rencana Pembelajaran Mingguan yang ditambahkan</p>
        @endif
    </div>

    <!-- ===== KOMPONEN PENILAIAN ===== -->
    <div class="section">
        <div class="section-title">G. KOMPONEN PENILAIAN</div>

        @php
            $assessmentPlan = is_string($rps->assessment_plan) ? json_decode($rps->assessment_plan, true) : ($rps->assessment_plan ?? []);
        @endphp

        @if (!empty($assessmentPlan))
        <table class="content-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Komponen</th>
                    <th width="45%">Kriteria Penilaian</th>
                    <th width="15%">Waktu</th>
                    <th width="10%" class="text-center">Bobot %</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBobot = 0; @endphp
                @foreach($assessmentPlan as $index => $item)
                @php $totalBobot += (int)($item['weight'] ?? 0); @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['component_name'] ?? '-' }}</td>
                    <td>{{ $item['criteria'] ?? '-' }}</td>
                    <td>{{ $item['time'] ?? '-' }}</td>
                    <td class="text-center">{{ $item['weight'] ?? 0 }}%</td>
                </tr>
                @endforeach
                <tr style="background-color: #f9f9f9;">
                    <td colspan="4" class="text-right text-bold">TOTAL BOBOT</td>
                    <td class="text-center text-bold">{{ $totalBobot }}%</td>
                </tr>
            </tbody>
        </table>
        @else
        <p class="table-note">Belum ada Komponen Penilaian yang ditambahkan</p>
        @endif
    </div>

    <!-- ===== TANDA TANGAN ===== -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div>Koordinator Program Studi</div>
            <div class="signature-line"></div>
            <div style="margin-top: 3px;">{{ $rps->headOfProgram->name ?? 'Belum Ditentukan' }}</div>
        </div>
        <div class="signature-box">
            <div>Penyusun</div>
            <div>&nbsp;</div>
            <div class="signature-line"></div>
            <div style="margin-top: 3px;">{{ $rps->lecturer->name ?? 'Belum Ditentukan' }}</div>
        </div>
    </div>

</body>
</html>
