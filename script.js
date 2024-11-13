if(screen.width <= 992){
    var open_nav = false;
    $('.nav-bar').remove();
    let parent_nav = $('.parent-nav');
    let icon_nav = $('<div class="icon-nav-bar"><i class="logo-nav-open fa-solid fa-bars"></i></div>');
    parent_nav.append(icon_nav);
    $('.icon-nav-bar').on('click' , function() {
        if(!open_nav){
            let div_nav_mobile = $('<div class="nav-mobile-parent"></div>');
            $('.nav-mobile-parent').remove();
            let nav_home = $("<a class='nav-link active' href='index.php'>Accueil</a>")
            let nav_articles = $('<a class="nav-link" href="index.php?action=articles">Articles</a>')
            
            let nav_add_article = $('<a class="nav-link" href="index.php?action=createArticle">Ajouter un Article</a>')
            let nav_gestion = $('<a class="nav-link" href="index.php?action=management">Gestion</a>')
            let nav_profile = $('<a class="nav-link" href="index.php?action=profile">Profile</a>')
            let nav_disconnect = $('<a class="nav-link" href="index.php?action=disconnect">Déconnexion</a>')
            let nav_connection = $('<a class="nav-link" href="index.php?action=login">Connexion</a>')
            let nav_register = $('<a class="nav-link" href="index.php?action=register">Enregistrement</a>')
            div_nav_mobile.append(nav_home);
            div_nav_mobile.append(nav_articles);

            if(is_admin){
                div_nav_mobile.append(nav_gestion);
            } 

            if(connect){
                div_nav_mobile.append(nav_add_article);
                div_nav_mobile.append(nav_profile);                
                div_nav_mobile.append(nav_disconnect);
            }else{
                div_nav_mobile.append(nav_connection);
                div_nav_mobile.append(nav_register);
            }

            parent_nav.prepend(div_nav_mobile);
            $('.logo-nav-open').remove();
            var logo_nav_close = $('<i class="logo-nav-close fa-solid fa-xmark"</i>');
            $('.icon-nav-bar').append(logo_nav_close);
            var height_nav = 0;

            $('.nav-link').each(function(index){
                height_nav+=$('.nav-link')[index].offsetHeight;
            })

            $('.nav-mobile-parent').animate({
                height : height_nav + "px",
            },1000)
            open_nav = true;
        }else {
            $('.logo-nav-close').remove();
            var logo_nav_open = $('<i class="logo-nav-open fa-solid fa-bars"</i>');
            $('.icon-nav-bar').append(logo_nav_open);
            $('.nav-mobile-parent').animate({
                height : "0px",
            },1000, function(){
                $('.nav-mobile-parent').remove();
            })
            open_nav = false;
        }
        
    })
}