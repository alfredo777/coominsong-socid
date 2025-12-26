
        document.addEventListener('DOMContentLoaded', function() {
            const headingElement = document.getElementById('main-heading');
            const textToType = "Estamos transformando el sitio."; // Texto actualizado
            let index = 0;

            // Añadir el cursor al final del h1
            headingElement.innerHTML = '<span class="typing-text"></span><span class="typing-cursor"></span>';
            const textElement = headingElement.querySelector('.typing-text');

            function type() {
                if (index < textToType.length) {
                    textElement.textContent += textToType.charAt(index);
                    index++;
                    setTimeout(type, 100); // Velocidad de escritura ligeramente ajustada
                }
            }

            type();
        });
    