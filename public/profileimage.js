window.onload = function() {
    loadProfileImage();
};

function loadProfileImage() {
    const profileImage = localStorage.getItem('profileImage');

    // Se houver uma imagem salva no localStorage
    if (profileImage) {
        const profileImageTopBar = document.getElementById('profileImageTopBar');
        const profileImageMenu = document.getElementById('profileImageMenu');

        // Carrega a imagem na barra superior e no dropdown
        if (profileImageTopBar) {
            profileImageTopBar.src = profileImage;
            profileImageTopBar.style.display = 'block';
        }
        if (profileImageMenu) {
            profileImageMenu.src = profileImage;
            profileImageMenu.style.display = 'block';
        }
    }
}