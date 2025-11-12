$("a.btn-fav").click(function () {
    $(".main-content").removeClass("hidden");
    $("ul.nav li a.active").removeClass("active");
    $(this).addClass("active");
    $(".nwo-cat-btn[data-change-cat='']").click();

    $("select#orderform-service option").remove();
    $("select#orderform-category-copy option").remove();
    $(".form-group.fields").addClass("hidden");

    let services = window.modules.siteOrder;
    // loop services
    let count = 0;
    $.each(services, function (index, value) {
        if (index == "services") {
            $("select#orderform-category-copy").html("");
            // loop services
            $.each(value, function (index, value) {
                // get this val
                let this_val = value.id;
                if (getCookie("favorite_service_" + this_val)) {
                    let cat_id = value.cid;

                    $("select#orderform-category option:not([remove='false'])").each(function () {
                        if ($(this).val() != cat_id) {
                            $(this).attr("remove", "true");
                        } else {
                            $(this).attr("remove", "false");
                        }
                    });
                    count++;
                }
            });
        }
    });

    $("select#orderform-category option[remove='true']").remove();

    if (count == 0) {
         $(".alert-fav.hidden").removeClass("hidden");
    	 $(".main-content").addClass("hidden");
    }

    $("select#orderform-category").trigger("change");

    // set 500ms
    setTimeout(function () {
        $("select#orderform-service option:not([remove='false'])").each(function () {
            let service_id = $(this).val();
            if (getCookie("favorite_service_" + service_id)) {
                $(this).attr("remove", "false");
            } else {
                $(this).attr("remove", "true");
            }
        });
        $("select#orderform-service option[remove='true']").remove();
        $("select#orderform-service").trigger("change");
    }, 500);
});

$("select#orderform-category").change(function () {
    if ($("a.btn-fav.active").length > 0) {
        setTimeout(function () {
            $("select#orderform-service option:not([remove='false'])").each(function () {
                let service_id = $(this).val();
                if (getCookie("favorite_service_" + service_id)) {
                    $(this).attr("remove", "false");
                } else {
                    $(this).attr("remove", "true");
                }
            });
            $("select#orderform-service option[remove='true']").remove();
            $("select#orderform-service").trigger("change");
        }, 100);
    }
    let icon = $(this).find("option:selected").attr("data-icon");
    setTimeout(function () {
        $("select#orderform-service option").attr("data-icon", icon);
        $("select#orderform-service").trigger("change");
    }, 10);
});

setTimeout(function () {
    let icon = $("select#orderform-service").find("option:selected").attr("data-icon");
    $("select#orderform-service option").attr("data-icon", icon);
    $("select#orderform-service").trigger("change");
}, 100);


$("button.favorite").click(function () {
    let service_id = $(this).attr("data-service-id");
    $(this).toggleClass("active");

    // add to favorite
    if ($(this).hasClass("active")) {
        // setcookie
        setCookie("favorite_service_" + service_id, service_id, 365);
    } else {
        // remove cookie
        setCookie("favorite_service_" + service_id, service_id, -1);
    }
});

if ($(".service-item").length > 0) {
    $(".service-item").each(function () {
        let service_id = $(this).attr("data-service-id");
        if (getCookie("favorite_service_" + service_id)) {
            $(this).find(".favorite").addClass("active");
            $(this).attr("data-fav", "true");
        } else {
            $(this).attr("data-fav", "false");
        }
    });
}

// setcookie, getcookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const filterServicesInput = document.getElementById('filterServicesInput');
if (filterServicesInput) {
    const serviceTitle = document.querySelectorAll('.service-cat-side');
    const serviceHeads = document.querySelectorAll('.service-category > .card-header');
    const nothingFound = document.querySelector('.nothing-found');
    const searchTextWrite = document.getElementById('search-text-write');

    filterServicesInput.addEventListener('keyup', e => {
        const keyword = e.target.value;
        $('.service-item').each(function () {
            var text = $(this).text().toLowerCase();
            if (text.indexOf(e.target.value.toLowerCase()) == -1) {
                $(this).addClass('hidden');
            } else {
                $(this).removeClass('hidden');
            }
        });

        const catCards = document.querySelectorAll('.service-category');
        [...catCards].forEach(card => {
            const itemsHidden = card.querySelectorAll('.service-item.hidden');
            const items = card.querySelectorAll('.service-item');
            if (itemsHidden.length == items.length) {
                card.style.display = 'none';
                card.classList.add('empty');
            } else {
                card.style.display = '';
                card.classList.remove('empty');
            }
        })

        const catCardsCount = catCards.length;
        // empty cards
        const emptyCards = document.querySelectorAll('.service-category.empty');
        console.log(emptyCards.length, catCardsCount);
        if (emptyCards.length == catCardsCount) {
            nothingFound.style.display = '';
            searchTextWrite.innerHTML = keyword;
        } else {
            nothingFound.style.display = 'none';
            searchTextWrite.innerHTML = '';
        }
    });
}

$(document).ready(function() {
    $(".hsob").click(function() {
        var e = $(this).attr("for");
        $(".home-tabs > .tab").removeClass("active"), $(".home-tabs > #" + e + ".tab").addClass("active"), $(".hsob").removeClass("active"), $(this).addClass("active")
    }), $(".slider-btn").click(function() {
        var e = $(this).attr("for");
        $(".slider-content > .slider-tab").removeClass("active"), $(".slider-content > #" + e + ".slider-tab").addClass("active"), $(".slider-btn").removeClass("active"), $(this).addClass("active")
    })
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})


$('.dd-custom .dropdown-item').click(function(event){
    event.stopPropagation();
});


$('.home-ss-tab').click(function(){
  if($(this).hasClass('active')){
      $(this).find('.ss-tab-content').slideToggle(200);
      $(this).toggleClass('active');
  }else {
      $('.home-ss-tab').removeClass('active');
      $('.home-ss-tab > .ss-tab-content').slideUp(200);
      $(this).find('.ss-tab-content').slideToggle(200);
      $(this).toggleClass('active');
  }
});

const upHeader = document.querySelector('#up-header');
  if (upHeader) {
    document.addEventListener('scroll', () => {
      window.scrollY > 100 ? upHeader.classList.add('active') : upHeader.classList.remove('active');
    });
}

const headerScroll = () => {
    if (window.scrollY > 10) {
        document.querySelector('#header').classList.add('fixed');
    } else {
        document.querySelector('#header').classList.remove('fixed');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#header')) {
        headerScroll();
    }
});

window.addEventListener('scroll', e => {
    headerScroll();
})

function noAuthMenu() {
		$('.hm-wrapper').toggleClass('active');
        $('.sm-header').toggleClass('active');
		$('body').toggleClass('stop-body');
}

var appBody = document.getElementsByClassName('app-body');
var hmm = false;

const homeMenuToggle = () => {
    if (hmm) {
        document.querySelector('.app-body').classList.remove('menu-active');
        setTimeout(() => {
            hmm = false;
        }, 10);
    } else {
        document.querySelector('.app-body').classList.add('menu-active');
        setTimeout(() => {
            hmm = true;
        }, 10);
    }
}

if (appBody[0]) {
    appBody = appBody[0];

    appBody.addEventListener('click', e => {
        if (hmm) {
            homeMenuToggle();
        }
    });

    appBody.addEventListener('scroll', _ => {
        wpos = appBody.scrollTop;
        homeHeaderScroll(wpos);
    });
} else {
    appBody = false;
}

/** header scroll */

const dbMenuToggle = document.getElementById('db-menuToggle');
const sidebar = document.getElementById('dash');

const dMenuToggle = () => {
    sidebar.classList.toggle('sidebar-active')
}

if (dbMenuToggle) {
    dbMenuToggle.addEventListener('click', e => {
        sidebar.classList.toggle('sidebar-active')
    })
}

const hdBtn = document.getElementById('hd-btn');
var hdStat = false;

if (hdBtn) {
    const hdRight = document.querySelector('.header-right');
    var items2 = hdRight.querySelectorAll('.hdi');

    document.addEventListener("DOMContentLoaded", _ =>  { 
        hdRight.style.opacity = 1;
        hdRight.classList.add('mobHid')
    });

    hdBtn.addEventListener('click', e => {
        if (!hdStat) {
            // opening
            hdRight.style.display = 'block';
            hdBtn.innerHTML = '<i class="fal fa-chevron-up">';
            var i = 0;
            [...items2].forEach(item => {
                setTimeout(() => {
                    item.classList.add('active');
                }, 100 * i);
                i++
            });

            hdStat = true;
        } else {
            // opening
            hdBtn.innerHTML = '<i class="fal fa-chevron-down">';
            var i = items2.length;
            [...items2].forEach(item => {
                setTimeout(() => {
                    item.classList.remove('active');
                }, 100 * i);
                i = i-1;
            });
            setTimeout(() => {
                hdRight.style.display = 'none';
            }, 100 * i);

            hdStat = false;
        }
    });
}

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

const themeButtons = {
    dark: document.querySelector(".dark-btn"),
    light: document.querySelector(".light-btn")
};

const htmlElement = document.getElementsByTagName("HTML")[0];

function changeTheme(theme) {
    localStorage.lightMode = theme;
    htmlElement.setAttribute("class", theme === 'auto' ? (window.matchMedia("(prefers-color-scheme: dark)").matches ? 'dark' : 'light') : theme);
    Object.values(themeButtons).forEach(button => button.classList.remove("active"));
    themeButtons[theme].classList.add("active");
}

function selectInitialTheme() {
    const savedTheme = localStorage.getItem("lightMode") || 'auto';
    if (savedTheme === 'auto') {
        changeToAutoTheme();
    } else {
        changeTheme(savedTheme);
    }
}

themeButtons.dark.addEventListener('click', () => changeTheme('dark'));
themeButtons.light.addEventListener('click', () => changeTheme('light'));

selectInitialTheme();

function copy(text, message, icon) {
	var copyinput = document.createElement("input");
	copyinput.setAttribute("type", "text");
	copyinput.setAttribute("value", text);
	copyinput.style.position = "absolute";
	copyinput.style.left = "-9999px";

	document.body.appendChild(copyinput);
	copyinput.select();
	document.execCommand("copy");
	document.body.removeChild(copyinput);
	notify(icon, message)
}

function notify(icon, message) {
   var e = document.getElementById("notify");
   notifyText.innerHTML = '<i class="far ' + icon + '"></i> ' + '<span>' + message + '</span>';
   e.classList.add("show"), setTimeout(function () {
      e.classList.remove("show")
   }, 3e3)
}

function setAmount(val) {
    var setamount = document.getElementById("amount");
    setamount.value = val
}

function filterCategory(e) {
	"all" == e ? $("[data-filter-table-category-id]").removeClass("hidden") : ($("[data-filter-table-category-id]").addClass("hidden"), $("[data-filter-table-category-id=" + e + "]").removeClass("hidden"))
}