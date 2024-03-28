$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            type: "POST",
            url: "/elitecars/atualizar_atividade.php",
            success: function(response) {
            },
            error: function(error) {
                console.error("Erro na solicitação AJAX:", error);
            }
        });
    }, 30000);
});
