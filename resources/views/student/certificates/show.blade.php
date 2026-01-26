<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $module->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F9FAFB;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 12px;
            z-index: 100;
        }

        .no-print a, .no-print button {
            padding: 14px 28px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .no-print a { 
            background: white; 
            color: #111827; 
            border: 1px solid #E5E7EB;
        }
        .no-print button { 
            background: #000; 
            color: white; 
        }
        .no-print a:hover { 
            border-color: #000;
        }
        .no-print button:hover { 
            background: #e69138; 
        }

        /* Certificate - A4 Landscape */
        .certificate {
            width: 297mm;
            height: 210mm;
            background: linear-gradient(145deg, #fffef8 0%, #faf8f0 50%, #f5f0e1 100%);
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }

        /* Ornamental Corner Decorations */
        .corner { position: absolute; width: 80px; height: 80px; opacity: 0.6; }
        .corner svg { width: 100%; height: 100%; fill: #c9a227; }
        .corner-tl { top: 15mm; left: 15mm; }
        .corner-tr { top: 15mm; right: 15mm; transform: scaleX(-1); }
        .corner-bl { bottom: 15mm; left: 15mm; transform: scaleY(-1); }
        .corner-br { bottom: 15mm; right: 15mm; transform: scale(-1); }

        /* Golden Border Frame */
        .frame-outer {
            position: absolute;
            inset: 8mm;
            border: 3px solid #c9a227;
            pointer-events: none;
        }
        .frame-inner {
            position: absolute;
            inset: 12mm;
            border: 1px solid #c9a227;
            pointer-events: none;
        }
        .frame-accent {
            position: absolute;
            inset: 10mm;
            border: 1px solid rgba(201, 162, 39, 0.3);
            pointer-events: none;
        }

        /* Decorative Lines */
        .line-decoration {
            width: 180px;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #c9a227 50%, transparent 100%);
            margin: 0 auto;
        }

        /* Content */
        .content {
            padding: 20mm 30mm;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        /* Header */
        .header { margin-bottom: 4mm; }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 3mm 6mm;
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
            color: #d4a853;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }

        .institution {
            font-size: 10px;
            color: #8b7355;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* Award Emblem */
        .emblem {
            width: 70px;
            height: 70px;
            margin: 3mm auto;
            position: relative;
        }
        .emblem svg { width: 100%; height: 100%; }

        /* Title */
        .title { margin-bottom: 4mm; }

        .title h1 {
            font-family: 'Cinzel', serif;
            font-size: 52px;
            font-weight: 600;
            letter-spacing: 12px;
            text-transform: uppercase;
            background: linear-gradient(180deg, #c9a227 0%, #8b6914 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2mm;
        }

        .title-sub {
            font-family: 'Cormorant Garamond', serif;
            font-size: 14px;
            color: #6b5b47;
            letter-spacing: 6px;
            text-transform: uppercase;
            font-style: italic;
        }

        /* Recipient */
        .recipient { margin-bottom: 4mm; }

        .recipient-label {
            font-family: 'Cormorant Garamond', serif;
            font-size: 13px;
            color: #8b7355;
            font-style: italic;
            margin-bottom: 2mm;
        }

        .recipient-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 42px;
            font-weight: 600;
            color: #1a1a2e;
            padding: 0 20mm;
            position: relative;
        }
        .recipient-name::after {
            content: '';
            position: absolute;
            bottom: -3mm;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #c9a227 50%, transparent 100%);
        }

        /* Description */
        .description {
            margin: 5mm 0;
            max-width: 180mm;
        }

        .description p {
            font-family: 'Cormorant Garamond', serif;
            font-size: 14px;
            color: #5a5040;
            line-height: 1.6;
        }

        .module-title {
            font-family: 'Cinzel', serif;
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 2mm 0;
        }

        .skill-badge {
            display: inline-block;
            padding: 1.5mm 5mm;
            background: linear-gradient(135deg, #c9a227 0%, #d4a853 100%);
            color: #1a1a2e;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        /* Grade */
        .grade-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 4mm 0;
            padding: 4mm 8mm;
            background: linear-gradient(135deg, rgba(201,162,39,0.1) 0%, rgba(201,162,39,0.05) 100%);
            border: 1px solid rgba(201,162,39,0.3);
        }

        .grade-icon {
            width: 40px;
            height: 40px;
        }
        .grade-icon svg { width: 100%; height: 100%; fill: #c9a227; }

        .grade-content { text-align: left; }
        .grade-label {
            font-size: 8px;
            color: #8b7355;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .grade-value {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 700;
            color: #c9a227;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 200mm;
            margin-top: auto;
            padding-top: 3mm;
        }

        .signature-block { text-align: center; min-width: 70mm; }

        .signature-label {
            font-size: 8px;
            color: #8b7355;
            margin-bottom: 8mm;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .signature-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            font-style: italic;
            color: #1a1a2e;
            padding-bottom: 1mm;
            border-bottom: 1px solid #c9a227;
            display: inline-block;
            min-width: 50mm;
        }

        .signature-title {
            font-size: 8px;
            color: #8b7355;
            margin-top: 1mm;
            letter-spacing: 1px;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 6mm;
            left: 0;
            right: 0;
            text-align: center;
        }

        .certificate-id {
            font-size: 7px;
            color: #b0a090;
            letter-spacing: 2px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Print */
        @media print {
            @page { size: A4 landscape; margin: 0; }
            body { background: white; padding: 0; min-height: auto; display: block; }
            .no-print { display: none !important; }
            .certificate { box-shadow: none; margin: 0; page-break-inside: avoid; }
            .title h1 {
                background: none !important;
                -webkit-background-clip: unset !important;
                -webkit-text-fill-color: #c9a227 !important;
                background-clip: unset !important;
                color: #c9a227 !important;
            }
        }

        /* Mobile Responsive - Horizontal Scroll */
        @media screen and (max-width: 1200px) {
            body { padding: 10px; }
            .certificate {
                width: 100%;
                max-width: 297mm;
                height: auto;
                min-height: auto;
                aspect-ratio: 297 / 210;
                transform-origin: top center;
            }
            .no-print {
                position: fixed;
                top: auto;
                bottom: 20px;
                left: 20px;
                right: 20px;
                flex-direction: column;
                gap: 8px;
            }
            .no-print a, .no-print button {
                text-align: center;
                padding: 12px 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .certificate {
                transform: scale(0.5);
                transform-origin: top center;
                margin-bottom: -50%;
            }
            .content { padding: 15mm 20mm; }
            .title h1 { font-size: 36px; letter-spacing: 6px; }
            .recipient-name { font-size: 28px; }
            .module-title { font-size: 14px; }
            .signatures { flex-direction: column; gap: 8mm; }
        }

        @media screen and (max-width: 480px) {
            .certificate {
                transform: scale(0.35);
                margin-bottom: -65%;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <a href="{{ route('student.modules.show', $module) }}">Kembali</a>
        <button onclick="window.print()">Cetak Sertifikat</button>
    </div>

    <div class="certificate">
        <!-- Decorative Frames -->
        <div class="frame-outer"></div>
        <div class="frame-accent"></div>
        <div class="frame-inner"></div>

        <!-- Corner Ornaments -->
        <div class="corner corner-tl">
            <svg viewBox="0 0 100 100"><path d="M0,0 L100,0 L100,10 L10,10 L10,100 L0,100 Z M20,20 Q50,20 50,50 Q50,20 80,20 L80,30 Q60,30 60,50 Q60,30 40,30 Q40,50 40,70 L30,70 L30,30 Q30,20 20,20 Z"/></svg>
        </div>
        <div class="corner corner-tr">
            <svg viewBox="0 0 100 100"><path d="M0,0 L100,0 L100,10 L10,10 L10,100 L0,100 Z M20,20 Q50,20 50,50 Q50,20 80,20 L80,30 Q60,30 60,50 Q60,30 40,30 Q40,50 40,70 L30,70 L30,30 Q30,20 20,20 Z"/></svg>
        </div>
        <div class="corner corner-bl">
            <svg viewBox="0 0 100 100"><path d="M0,0 L100,0 L100,10 L10,10 L10,100 L0,100 Z M20,20 Q50,20 50,50 Q50,20 80,20 L80,30 Q60,30 60,50 Q60,30 40,30 Q40,50 40,70 L30,70 L30,30 Q30,20 20,20 Z"/></svg>
        </div>
        <div class="corner corner-br">
            <svg viewBox="0 0 100 100"><path d="M0,0 L100,0 L100,10 L10,10 L10,100 L0,100 Z M20,20 Q50,20 50,50 Q50,20 80,20 L80,30 Q60,30 60,50 Q60,30 40,30 Q40,50 40,70 L30,70 L30,30 Q30,20 20,20 Z"/></svg>
        </div>

        <div class="content">
            <!-- Header -->
            <div class="header">
                <div class="logo-badge">✦ TechALearn ✦</div>
                <div class="institution">Teknologi Informasi • UIN Ar-Raniry Banda Aceh</div>
            </div>

            <!-- Award Emblem -->
            <div class="emblem">
                <svg viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#c9a227" stroke-width="2"/>
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#c9a227" stroke-width="1" opacity="0.5"/>
                    <path d="M50 15 L55 35 L75 35 L60 47 L65 67 L50 55 L35 67 L40 47 L25 35 L45 35 Z" fill="#c9a227"/>
                </svg>
            </div>

            <!-- Title -->
            <div class="title">
                <h1>Sertifikat</h1>
                <p class="title-sub">Penyelesaian Modul Pembelajaran</p>
            </div>

            <div class="line-decoration"></div>

            <!-- Recipient -->
            <div class="recipient">
                <p class="recipient-label">Dengan bangga diberikan kepada</p>
                <h2 class="recipient-name">{{ $user->name }}</h2>
            </div>

            <!-- Description -->
            <div class="description">
                <p>Atas dedikasi dan keberhasilannya menyelesaikan seluruh materi pembelajaran pada modul</p>
                <p class="module-title">"{{ $module->title }}"</p>
                <span class="skill-badge">✦ Tingkat {{ $module->skill_level }} ✦</span>
            </div>

            <!-- Grade -->
            @if($grade)
            <div class="grade-section">
                <div class="grade-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                </div>
                <div class="grade-content">
                    <p class="grade-label">Nilai Akhir</p>
                    <p class="grade-value">{{ $grade }}</p>
                </div>
            </div>
            @endif

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-block">
                    <p class="signature-label">Tanggal Penerbitan</p>
                    <p class="signature-name">{{ now()->isoFormat('D MMMM Y') }}</p>
                    <p class="signature-title">Banda Aceh, Indonesia</p>
                </div>
                <div class="signature-block">
                    <p class="signature-label">Instruktur</p>
                    <p class="signature-name">
                        @if($module->creator)
                            {{ $module->creator->name }}
                        @else
                            Tim TechALearn
                        @endif
                    </p>
                    <p class="signature-title">TechALearn Academy</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p class="certificate-id">
                    CERTIFICATE ID: TECH-{{ strtoupper(substr(md5($user->id . $module->id . $module->created_at), 0, 12)) }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
