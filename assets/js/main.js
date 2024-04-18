/** 
 * Navigation
 */ 
jQuery(document).ready(function($) {
	document.addEventListener("click", documentActions);

	function documentActions(e) {
		const targetElement = e.target;

		if (targetElement.closest('.icon-menu')) {
			document.documentElement.classList.toggle('menu-open');
		}
	}
});	

/**
 * Live Search with AJAX
 */
jQuery(document).ready(function($) {
	// Універсально
	const search_input = $('input[name="s"]');
    // const search_input = $(".search-form__input");
    const search_results = $(".ajax-search");

    search_input.keyup(function () {
        let search_value = $(this).val();

        if (search_value.length > 2) { // кількість символів
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: "POST",
                data: {
                    "action": "ajax_search", // functions.php
                    "term": search_value
                },
                success: function (results) {
                    search_results.fadeIn(200).html(results);
                }
            });
        } else {
            search_results.fadeOut(200);
        }
    });

    // закриття пошуку при кліку по за нього
    $(document).mouseup(function(e) {
        if (
            (search_input.has(e.target).length === 0) &&
            (search_results.has(e.target).length === 0)
        ) {
            search_results.fadeOut(200);
        };
    });
});

/**
 * Scroll To Top Button
 * 
 * https://dev.to/ljcdev/scroll-to-top-button-in-vanilla-js-beginners-2nc
 * 
 */
document.addEventListener("scroll", handleScroll);
// get a reference to our predefined button
var scrollToTopBtn = document.querySelector(".scrollToTopBtn");

function handleScroll() {
  var scrollableHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var GOLDEN_RATIO = 0.1;

  if ((document.documentElement.scrollTop / scrollableHeight ) > GOLDEN_RATIO) {
    //show button
    if(!scrollToTopBtn.classList.contains("showScrollBtn"))
    scrollToTopBtn.classList.add("showScrollBtn")
  } else {
    //hide button
    if(scrollToTopBtn.classList.contains("showScrollBtn"))
    scrollToTopBtn.classList.remove("showScrollBtn")
  }
}

scrollToTopBtn.addEventListener("click", scrollToTop);

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
}

/**
 * AJAX Comments
 * 
 * https://misha.agency/course/ajax-comments
 * 
 */
jQuery( function($) {
	// дія при відправленні форми коментаря
	$( '#commentform' ).submit( function() {
 
		var commentForm = $(this),
				respond = $( '#respond' ),
				commentList = $( '.comment-list' ); // .comment-list іноді має інший клас
		var submitProcessText = MPUNIVERSALMAINSCRIPT.submitProcessText;
		var sendCommentText = MPUNIVERSALMAINSCRIPT.sendCommentText;
		var errorAddingCommentText = MPUNIVERSALMAINSCRIPT.errorAddingCommentText;
		var serverNotRespondingText = MPUNIVERSALMAINSCRIPT.serverNotRespondingText;
 
		// відправляємо запит
		$.ajax({
			type : 'POST',
			url : MPUNIVERSALMAINSCRIPT.ajaxUrl,
			data : commentForm.serialize() + '&action=sendcomment',
			beforeSend: function( xhr ) {
				// змінюємо текст кнопки перед відправкою коментаря
				$( '#submit' ).val( submitProcessText );
				// clean previous error messages
				jQuery('.comment-list').remove();
			},
			error: function (request, status, error) {
				// обробляємо помилки
				if ( status == 500 ) {
					alert( errorAddingCommentText );
				} else if ( status == 'timeout' ) {
					alert( serverNotRespondingText );
				} else {
					
					// вбудовані помилки WordPress
					var errormsg = request.responseText;
					var string1 = errormsg.split("<p>");
					var string2 = string1[1].split("</p>");
					alert(string2[0]);
				}
 
			},
			success: function( newComment ) {
				// console.log( newComment );
				if ( $( '.comment-list li' ).length > 0 ) { // якщо є коментарі
 
					if ( respond.parent().hasClass( 'comment' ) ) { // якщо дочірній коментар
 
						if ( respond.parent().children( '.children' ).length > 0 ) { // якщо дочірні вже є
							respond.parent().children( '.children' ).append( newComment );
						} else { // якщо перший дочірній
							respond.after( '<ol class="children">' + newComment + '</ol>' );
						}
 
					} else { // якщо звичайний коментар
						commentList.append( newComment );
					}
 
				} else { // якщо коментарі відсутні
					respond.before( '<ol class="comment-list">' + newComment + '</ol>' );
				}
 
				$( '#cancel-comment-reply-link' ).trigger( "click" );
				$( '#submit' ).val( sendCommentText );
				$( '#comment' ).val(''); // очищуємо поле комментаря
 
			}
		} );
 
		return false;
	});
});