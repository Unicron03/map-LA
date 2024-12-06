let currentForm = null;

function toggleForm(formId) {
    // ---------------------------Initialisation des éléments à modifier-----------------------------------------
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const changeDiv = document.getElementById('change');
    const registerButton = document.getElementById('register-button');
    const loginButton = document.getElementById('login-button');
    const successMessage = document.getElementById('success-message'); // Sélectionnez le message de succès

    if (successMessage) {
        successMessage.remove(); // Effacez le message d'inscription réussie
    }

    if (currentForm === formId) {
        // Si on clique sur le même bouton, on remet le contenu initial
        loginForm.classList.remove('active');
        registerForm.classList.remove('active');
        // -----------------------------Ce reécrit les divs supprimer regarder si je peux pas mieux obtimiser-------------(To Check)
        changeDiv.innerHTML = "<div class='panel-controls'><br><a>Cacher les points marqués</a><br><a>Déselectionner toutes les catégories</a><br><a>Sélectionner toutes les catégories</a></div>";
        currentForm = null;
        loginButton.style.display = 'inline';
        registerButton.style.display = 'none'; // Cacher le bouton inscription
    } else {
        // Afficher le formulaire correspondant
        loginForm.classList.remove('active');
        registerForm.classList.remove('active');
        document.getElementById(formId).classList.add('active');
        changeDiv.innerHTML = ''; // Vider le contenu initial

        if (formId === 'register-form') {
            registerButton.style.display = 'none'; // Cacher le bouton inscription pendant l'inscription
        } else {
            registerButton.style.display = 'inline'; // Montrer le bouton inscription uniquement avec le formulaire de connexion
        }
        
        currentForm = formId;
    }
}