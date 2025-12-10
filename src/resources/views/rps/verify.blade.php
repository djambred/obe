<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi RPS - {{ $rps->course->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: white;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #f0f0f0;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }

        .university-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .university-address {
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }

        .university-contact {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        .title {
            background: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .title h1 {
            font-size: 22px;
            color: #333;
            margin-bottom: 5px;
        }

        .content {
            padding: 30px;
        }

        .stamp-container {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .stamp {
            display: inline-block;
            border: 8px solid {{ $rps->status === 'Approved' ? '#28a745' : '#dc3545' }};
            color: {{ $rps->status === 'Approved' ? '#28a745' : '#dc3545' }};
            font-size: 48px;
            font-weight: bold;
            padding: 20px 40px;
            transform: rotate(-5deg);
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            flex: 0 0 180px;
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        .info-value {
            flex: 1;
            color: #333;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: {{ $rps->status === 'Approved' ? '#28a745' : '#ffc107' }};
            color: white;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 2px solid #e0e0e0;
        }

        .footer-text {
            font-size: 12px;
            color: #666;
            line-height: 1.6;
        }

        .download-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }

            .stamp {
                font-size: 36px;
                padding: 15px 30px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $university->name ?? 'University' }}" class="logo">
            @endif
            <div class="university-name">{{ $university->name ?? 'Universitas' }}</div>
            @if($university && $university->address)
                <div class="university-address">
                    {{ $university->address }}
                </div>
            @endif
            @if($university && ($university->phone || $university->email || $university->website))
                <div class="university-contact">
                    @if($university->phone)Tlp: {{ $university->phone }}@endif
                    @if($university->phone && $university->email) | @endif
                    @if($university->email){{ $university->email }}@endif
                    @if(($university->phone || $university->email) && $university->website) | @endif
                    @if($university->website){{ $university->website }}@endif
                </div>
            @endif
        </div>

        <!-- Title -->
        <div class="title">
            <h1>Verifikasi Data Rencana Pembelajaran Semester</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Stamp -->
            <div class="stamp-container">
                <div class="stamp">
                    {{ $rps->status === 'Approved' ? 'VALID' : ($rps->status === 'Draft' ? 'DRAFT' : 'REVIEW') }}
                </div>
            </div>

            <!-- Info Table -->
            <div class="info-table">
                <div class="info-row">
                    <div class="info-label">Tahun Kurikulum</div>
                    <div class="info-value">{{ $rps->academic_year }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mata Kuliah</div>
                    <div class="info-value">
                        <strong>{{ $rps->course->code }}</strong> - {{ $rps->course->name }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Semester</div>
                    <div class="info-value">{{ $rps->semester }}@if($rps->class_code) - Kelas {{ $rps->class_code }}@endif</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Program Studi</div>
                    <div class="info-value">{{ $rps->studyProgram->name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fakultas</div>
                    <div class="info-value">{{ $rps->faculty->name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dosen Koordinator</div>
                    <div class="info-value">{{ $rps->coordinator->name ?? ($rps->lecturer->name ?? '-') }}</div>
                </div>
                @if($rps->status === 'Approved' && $rps->headOfProgram)
                <div class="info-row">
                    <div class="info-label">Disetujui Oleh</div>
                    <div class="info-value">
                        {{ $rps->headOfProgram->name }}<br>
                        <small style="color: #999;">Ketua Program Studi {{ $rps->studyProgram->name ?? '' }}</small>
                    </div>
                </div>
                @endif
                @if($rps->approved_at)
                <div class="info-row">
                    <div class="info-label">Tanggal Persetujuan</div>
                    <div class="info-value">{{ $rps->approved_at->format('d F Y') }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge">{{ $rps->status }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                {{ $university->name ?? 'Universitas' }}<br>
                @if($university && $university->website)
                    <a href="http://{{ $university->website }}" style="color: #667eea; text-decoration: none;">{{ $university->website }}</a>
                @endif
            </div>
            @auth
                <a href="{{ route('rps.download-pdf', $rps->id) }}" class="download-btn">
                    📄 Download PDF
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
