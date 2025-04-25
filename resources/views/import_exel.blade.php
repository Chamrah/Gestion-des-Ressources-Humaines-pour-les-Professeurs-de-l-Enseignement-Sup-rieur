<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 11 Import Excel File</title>
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> --}}
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --danger: #ef4444;
            --light: #f9fafb;
            --dark: #1f2937;
            --gray: #6b7280;
        }




        /* body {
            background-color: #f3f4f6;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        } */

        .container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            transition: all 0.3s ease;
        }

        h2 {
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2 i {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-button {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            background-color: #f9fafb;
        }

        .file-input-button:hover {
            border-color: var(--primary);
            background-color: #f0f7ff;
        }

        .file-input-button i {
            font-size: 2rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .file-input-button span {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .file-input-button strong {
            color: var(--primary);
            font-weight: 600;
        }

        input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;

            text-align: center;
        }

        .btn i {
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #0d9488;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-info:hover {
            background: #0284c7;
            transform: translateY(-2px);
        }

        .alert {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.5s ease;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 0.5rem;
            color: var(--success);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .file-link:hover {
            color: #064e3b;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            .buttons-grid {
                grid-template-columns: 1fr;
            }

            h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 1.25rem;
                border-radius: 8px;
            }

            .file-input-button {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-file-upload"></i> Upload a File</h2>



            {{-- <div class="buttons-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload
                </button> --}}








        <form action="{{ route('import.upgrade') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="file">Sélectionner un fichier Excel</label>
                <div class="file-input-wrapper">
                    <div class="file-input-button">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Glissez-déposez votre fichier ou <strong>parcourir</strong></span>
                        <span class="file-name" id="file-name">Aucun fichier sélectionné</span>
                    </div>
                    <input type="file" name="excel_file" id="file" required onchange="updateFileName(this)">
                </div>
            </div>

            <div class="buttons-grid">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-robot"></i> Importer
                </button>
                <a href="{{ route('generate.employees') }}" class="btn btn-warning">
                    <i class="fas fa-sync-alt"></i> Mettre à jour le fichier
                </a>

                <a href="{{ asset('storage/nom1.xlsx') }}" class="btn btn-info" download>
                    <i class="fas fa-file-download"></i> Télécharger
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        {{ session('success') }}
                        <a href="{{ asset('storage/app/public/nom1.xlsx') }}" class="file-link" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Ouvrir le fichier
                        </a>
                    </div>
                </div>
            @endif
        </form>
        <form action="{{ route('run.upgrade') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="fas fa-robot"></i> Mise à jour automatique des grades & échelons
            </button>
        </form>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameElement = document.getElementById('file-name');
            if (input.files.length > 0) {
                fileNameElement.textContent = input.files[0].name;
                fileNameElement.style.color = '#10b981';
                fileNameElement.style.fontWeight = '600';
            } else {
                fileNameElement.textContent = 'Aucun fichier sélectionné';
                fileNameElement.style.color = '';
                fileNameElement.style.fontWeight = '';
            }
        }

        // Animation for buttons on hover
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                btn.style.transform = 'translateY(-2px)';
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = '';
            });
        });
    </script>
</body>
</html>