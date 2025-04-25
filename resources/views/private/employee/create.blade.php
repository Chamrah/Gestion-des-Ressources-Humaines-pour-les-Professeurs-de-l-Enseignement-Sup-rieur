@include('components.private.header')
@include('components.private.sidebar')

<section class="home-section" style="margin-bottom: 500px">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Ajouter un Employé</span>
    </div>
    <div class="mycontent">
        <div class="container-sm mt-5">
            <form action="{{ route('employee.store') }}" method="POST">
                @csrf

                <!-- TPR -->
                <div class="mb-3">
                    <label class="form-label">Doti</label>
                    <input type="text" name="tpr" class="form-control">
                </div>
                <div class="row">
                    <!-- Nom et Prénom en arabe -->
                    <div class="col-md-6">
                        <label for="nomar" class="form-label">النسب بالعربية / Nom (ar)</label>
                        <input type="text" name="nomar" id="nomar" class="form-control" oninput="updateNomComplet()">
                    </div>
                    <div class="col-md-6">
                        <label for="prenomar" class="form-label">الاسم بالعربية / Prénom (ar)</label>
                        <input type="text" name="prenomar" id="prenomar" class="form-control" oninput="updateNomComplet()">
                    </div>

                    <!-- Nom et Prénom en français -->
                    <div class="col-md-6">
                        <label for="nom" class="form-label">النسب / Nom</label>
                        <input type="text" name="first_name" id="nom" class="form-control" oninput="updateNomComplet()">
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">الاسم / Prénom</label>
                        <input type="text" name="last_name" id="prenom" class="form-control" oninput="updateNomComplet()">
                    </div>

                    <!-- Nom complet en arabe -->
                    <div class="col-md-6">
                        <label for="nom_complet_ar" class="form-label">الاسم الكامل بالعربية / Nom complet (ar)</label>
                        <input type="text" name="NOM_ET_PRENOM" id="nom_complet_ar" class="form-control" readonly>
                    </div>

                    <!-- Nom complet en français -->
                    <div class="col-md-6">
                        <label for="nom_complet_fr" class="form-label">الاسم الكامل بالفرنسية / Nom complet (fr)</label>
                        <input type="text" name="nom_prenom" id="nom_complet_fr" class="form-control" readonly>
                    </div>
                </div>
                <!-- Sexe -->
                <div class="mb-3">
                    <label class="form-label">Sexe / الجنس</label>
                    <select name="sex" class="form-select">
                        <option value="M">ذكر / Masculin</option>
                        <option value="F">أنثى / Féminin</option>
                    </select>
                </div>

                <!-- CIN -->
                <div class="mb-3">
                    <label class="form-label">CIN / رقم البطاقة الوطنية</label>
                    <input type="text" name="national_id" class="form-control">
                </div>

                <!-- Date de naissance -->
                <div class="mb-3">
                    <label class="form-label">Date de naissance / تاريخ الميلاد</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>

                <!-- Date de retraite -->


                <!-- D.R.M.C -->
                <div class="mb-3">
                    <label class="form-label">D.R.M.C / قرار الترسيم</label>
                    <input type="date" name="drmc" class="form-control">
                </div>

                <!-- D.R.M.ATT.S -->
                <div class="mb-3">
                    <label class="form-label">D.R.M.ATT.S / قرار الترسيم المؤقت</label>
                    <input type="date" name="drm_att_s" class="form-control">
                </div>

                <!-- Cadre -->
                <div class="mb-3">
                    <label class="form-label">Cadre / الإطار</label>
                    <input type="text" name="cadre" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Grade / درجة</label>
                    <input type="text" name="grade" class="form-control">
                </div>

                <!-- Date effet 1 -->
                <div class="mb-3">
                    <label class="form-label">Date Effet 1 / تاريخ السريان الأول</label>
                    <input type="date" name="date_effet1" class="form-control">
                </div>

                <!-- Échelon -->
                <div class="mb-3">
                    <label class="form-label">Échelon / السلم أو الدرجة</label>
                    <input type="text" name="ech" class="form-control">
                </div>

                <!-- Date effet 2 -->

                <div class="mb-3">
                    <label class="form-label">Date Effet 2 / تاريخ السريان الثاني</label>
                    <input type="date" name="date_effet2" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Date de retraite / تاريخ الإحالة على التقاعد</label>
                    <input type="date" name="date_retarite" class="form-control">
                </div>


                <!-- Indice -->
                <div class="mb-3">
                    <label class="form-label">Indice / الرقم الاستدلالي</label>
                    <input type="text" name="indice" class="form-control">
                </div>

                <!-- Département -->
                <div class="mb-3">
                    <label class="form-label">Département / الشعبة</label>
                    <input type="text" name="dep" class="form-control">
                </div>

                <!-- Spécialité -->
                <div class="mb-3">
                    <label class="form-label">Spécialité / التخصص</label>
                    <input type="text" name="specialite" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer / حفظ</button>
            </form>


        </div>
    </div>
</section>

<script>
    document.getElementById('image').addEventListener('change', function (e) {
        const img = document.getElementById('image-preview');
        const reader = new FileReader();
        reader.onload = function (event) {
            img.src = event.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    document.addEventListener("DOMContentLoaded", function() {
        const selectNationality = document.getElementById('nationality');

        fetch('{{ asset('data/countries.json') }}')
            .then(response => response.json())
            .then(data => {
                data.forEach(country => {
                    const nativeName = country.name.nativeName?.ara?.common || '';
                    const optionText = nativeName ? ${country.name.common}, ${nativeName} : country.name.common;
                    const option = new Option(optionText, country.name.common);
                    selectNationality.add(option);
                });
            });

        fetch('{{ asset('data/flags.json') }}')
            .then(response => response.json())
            .then(data => {
                data.forEach(country => {
                    const option = selectNationality.querySelector(option[value="${country.name}"]);
                    if (option && country.emoji) {
                        option.textContent = ${country.emoji} ${option.textContent};
                    }
                });
            });
    });
</script>
<script>
    // Mise à jour des champs Nom complet avec la combinaison des champs Nom et Prénom
    function updateNomComplet() {
        var nom = document.getElementById('nom').value;
        var prenom = document.getElementById('prenom').value;
        var nomar = document.getElementById('nomar').value;
        var prenomar = document.getElementById('prenomar').value;

        // Combinaison des champs Nom et Prénom pour le français
        var nomCompletFr = nom + ' ' + prenom;

        // Combinaison des champs Nom et Prénom pour l'arabe
        var nomCompletAr = nomar + ' ' + prenomar;

        // Mise à jour des champs Nom complet en arabe et en français
        document.getElementById('nom_complet_fr').value = nomCompletFr;
        document.getElementById('nom_complet_ar').value = nomCompletAr;
    }
</script>
@include('components.private.footer')
