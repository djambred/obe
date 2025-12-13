<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lecturer->name }} - Lecturer Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 15px;
            min-height: 100vh;
        }
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 25px;
            align-items: flex-start;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .profile-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            background: rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .profile-info h1 {
            margin: 0 0 5px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .profile-info .subtitle {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 12px;
        }
        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px 20px;
            font-size: 12px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .info-label {
            opacity: 0.8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 600;
        }
        .info-value {
            font-weight: 500;
            word-wrap: break-word;
            font-size: 13px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding: 20px 25px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .metric-card {
            text-align: center;
            padding: 12px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .metric-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 3px;
        }
        .metric-label {
            font-size: 11px;
            color: #6c757d;
            font-weight: 500;
        }
        .content-section {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
        }
        .content-section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #212529;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .section-title i {
            color: #667eea;
            font-size: 16px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 20px;
            margin-bottom: 12px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .detail-label {
            font-size: 11px;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .detail-value {
            font-size: 13px;
            color: #212529;
            word-wrap: break-word;
        }
        .badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }
        .badge {
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 500;
            border-radius: 15px;
            white-space: nowrap;
        }
        .position-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .position-item {
            padding: 10px;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-left: 3px solid #667eea;
            border-radius: 4px;
        }
        .position-name {
            font-weight: 600;
            color: #212529;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .position-unit {
            font-size: 12px;
            color: #6c757d;
        }
        .position-date {
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }
        .action-buttons a {
            padding: 7px 14px;
            font-size: 12px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .bio-text {
            font-size: 13px;
            line-height: 1.5;
            color: #333;
            word-wrap: break-word;
        }
        .footer-section {
            padding: 12px 25px;
            background: #f8f9fa;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
        }
        @media (max-width: 1024px) {
            .profile-header {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            .profile-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
                padding: 15px 20px;
                gap: 10px;
            }
        }
        @media (max-width: 768px) {
            .profile-photo-placeholder, .profile-photo {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }
            .profile-info h1 {
                font-size: 22px;
            }
            .profile-info-grid {
                grid-template-columns: 1fr;
                gap: 8px 15px;
            }
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .details-grid {
                grid-template-columns: 1fr;
                gap: 8px 15px;
            }
            .content-section {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Header dengan foto dan info -->
        <div class="profile-header">
            <!-- Foto -->
            <div>
                @if ($lecturer->photo)
                    <img src="{{ Storage::disk('minio')->url($lecturer->photo) }}" alt="{{ $lecturer->name }}" class="profile-photo">
                @else
                    <div class="profile-photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="profile-info">
                <h1>{{ $lecturer->name }}</h1>
                <p class="subtitle">
                    @if ($lecturer->studyProgram)
                        📚 {{ $lecturer->studyProgram->name }}
                    @endif
                </p>

                <div class="profile-info-grid">
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $lecturer->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $lecturer->phone ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIDN</span>
                        <span class="info-value">{{ $lecturer->nidn ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Rank</span>
                        <span class="info-value">{{ $lecturer->academic_rank ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if ($lecturer->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Employment</span>
                        <span class="info-value">{{ $lecturer->employment_status ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-number" style="color: #f39c12;">{{ $lecturer->sinta_score ?? '-' }}</div>
                <div class="metric-label">SINTA Score</div>
            </div>
            <div class="metric-card">
                <div class="metric-number" style="color: #27ae60;">{{ $lecturer->h_index ?? '-' }}</div>
                <div class="metric-label">H-Index</div>
            </div>
            <div class="metric-card">
                <div class="metric-number" style="color: #e74c3c;">{{ $lecturer->total_citations ?? 0 }}</div>
                <div class="metric-label">Total Citations</div>
            </div>
            <div class="metric-card">
                <div class="metric-number" style="color: #3498db;">{{ $lecturer->total_publications ?? 0 }}</div>
                <div class="metric-label">Publications</div>
            </div>
        </div>

        <!-- Section: Academic Details -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-graduation-cap"></i> Academic Information
            </div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Highest Education</span>
                    <span class="detail-value">{{ $lecturer->highest_education ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">SINTA ID</span>
                    <span class="detail-value">{{ $lecturer->sinta_id ?? 'Not linked' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">i10-Index</span>
                    <span class="detail-value">{{ $lecturer->i10_index ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Last Profile Sync</span>
                    <span class="detail-value">
                        @if ($lecturer->last_profile_sync)
                            {{ $lecturer->last_profile_sync->diffForHumans() }}
                        @else
                            Never synced
                        @endif
                    </span>
                </div>
            </div>

            <!-- External Links -->
            <div class="action-buttons">
                @if ($lecturer->sinta_id)
                    <a href="https://sinta.kemdiktisaintek.go.id/authors/profile/{{ $lecturer->sinta_id }}" target="_blank" class="btn-primary">
                        <i class="fas fa-link"></i> View on SINTA
                    </a>
                @endif
                @if ($lecturer->google_scholar_id)
                    <a href="https://scholar.google.com/citations?user={{ $lecturer->google_scholar_id }}" target="_blank" class="btn-success">
                        <i class="fas fa-link"></i> Google Scholar
                    </a>
                @endif
            </div>
        </div>

        <!-- Section: Expertise & Research -->
        @if ($lecturer->expertise_areas || $lecturer->research_interests)
            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-lightbulb"></i> Expertise & Research
                </div>

                @if ($lecturer->expertise_areas)
                    <div class="detail-label" style="margin-bottom: 8px;">Bidang Keahlian</div>
                    <div class="badges-container">
                        @foreach ((array) $lecturer->expertise_areas as $area)
                            @if (!is_array($area))
                                <span class="badge bg-primary">{{ $area }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if ($lecturer->research_interests)
                    <div class="detail-label" style="margin-top: 15px; margin-bottom: 8px;">Minat Penelitian</div>
                    <div class="badges-container">
                        @foreach ((array) $lecturer->research_interests as $interest)
                            @if (!is_array($interest))
                                <span class="badge bg-info">{{ $interest }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Section: Academic Positions -->
        @if ($lecturer->academic_positions && count($lecturer->academic_positions) > 0)
            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-briefcase"></i> Academic Positions
                </div>
                <ul class="position-list">
                    @foreach ($lecturer->academic_positions as $position)
                        <li class="position-item">
                            <div class="position-name">{{ $position['position'] ?? 'N/A' }}</div>
                            <div class="position-unit">{{ $position['unit'] ?? 'N/A' }}</div>
                            @if (isset($position['start_date']))
                                <div class="position-date">
                                    {{ \Carbon\Carbon::parse($position['start_date'])->format('M Y') }}
                                    @if (isset($position['end_date']))
                                        - {{ \Carbon\Carbon::parse($position['end_date'])->format('M Y') }}
                                    @else
                                        - Present
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section: Biography -->
        @if ($lecturer->biography)
            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-book"></i> Biography
                </div>
                <p class="bio-text">{{ $lecturer->biography }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer-section">
            <small>&copy; 2025 OBE System. All rights reserved.</small>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
