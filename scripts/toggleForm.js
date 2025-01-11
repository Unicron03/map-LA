let currentForm = null;

/**
 * Fonction qui gère le changement de menu du panneau
*/
function toggleForm(formId) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const panelControls = document.getElementById('panel-controls');
    const panelIcons = document.getElementById('panel-icons');

    if (currentForm == formId || registerForm.style.display == "block") {
        // Si on clique sur le même bouton, on remet le contenu initial
        loginForm.style.display = 'none';
        registerForm.style.display = 'none';
        panelControls.style.display = 'flex';
        panelIcons.style.display = 'flex';

        currentForm = null;
    } else {
        if (formId == "login-form") {
            loginForm.style.display = 'block';
        } else {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        }
        
        panelControls.style.display = 'none';
        panelIcons.style.display = 'none';
        
        currentForm = formId;
    }
}