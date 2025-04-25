@include('components.private.header')
@include('components.private.sidebar')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .profile-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .profile-container:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 20px;
    }

    .profile-image img {
        width: 100%;
        height: auto;
    }

    .social-icons {
        list-style-type: none;
        padding: 0;
    }

    .social-icons li {
        display: inline;
        margin-right: 10px;
    }

    .social-icons a {
        color: #007bff;
        /* Bootstrap primary color */
        font-size: 24px;
    }
</style>

<section class="home-section" style="margin-bottom: 500px">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">About</span>
    </div>
    <div class="container my-5 w-100">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body bg-light p-5">
                <h2 class="mb-4 text-primary fw-bold text-center">
                    À propos de l'application
                </h2>
                <p class="fs-5 text-secondary text-center">
                    Cette plateforme a été conçue pour simplifier la gestion des ressources humaines
                    dans l’enseignement supérieur.
                </p>
                <hr class="my-4">
                <div class="text-center">
                    <h4 class="text-dark">Développée par :</h4>
                    <ul class="list-unstyled fs-5">
                        <li><i class="bi bi-person-circle me-2 text-primary"></i><strong>Ziad Chamrah</strong></li>
                        <li><i class="bi bi-person-circle me-2 text-primary"></i><strong>Yahya Zaizi</strong></li>
                    </ul>
                </div>
                <div class="alert alert-info text-center mt-4" role="alert">
                    Ce projet reflète l’engagement de ses développeurs à créer des solutions efficaces, modernes et évolutives.
                </div>
            </div>
        </div>
    </div>
</section>

@include('components.private.footer')
