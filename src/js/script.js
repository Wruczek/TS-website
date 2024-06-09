var csrfToken = $('meta[name="csrf-token"]').attr("content")

$(function () {
    "use strict"

    // START string.format
    // parts from https://stackoverflow.com/a/4673436/5381375
    // Replace placeholders like [0] or {0} with arguments
    String.prototype.format = function() {
        var args = arguments
        return this.replace(/({|\[)(\d+)(]|})/g, function(match, x, number) {
            return typeof args[number] != 'undefined' ? args[number] : match
        })
    }
    // END string.format

    // START Time functions
    dayjs.extend(window.dayjs_plugin_localizedFormat)
    dayjs.extend(window.dayjs_plugin_relativeTime)

    console.log("Day.js locale set to " + dayjs.locale());

    updateRelativeTime();

    setInterval(function () {
        updateRelativeTime();
    }, 1000 * 60);
    // END Time functions

    // START Cookies
    if (!Cookies.get("tswebsite_cookie_consent")) {
        $(".cookiealert").addClass("show");
    }

    $(".acceptcookies").click(function () {
        Cookies.set("tswebsite_cookie_consent", true, {expires: 365});
        $(".cookiealert").removeClass("show");
    });
    // END Cookies

    $('*[data-connectionproblem="trigger"]').click(function (e) {
        e.preventDefault()
        $(this).siblings('*[data-connectionproblem="hidden"]').show();
        $(this).hide();
    });

    // Check if browser supports CSS variables, if not, display an old browser warning
    // Taken from Modernizr, MIT license
    var supportsFn = (window.CSS && window.CSS.supports.bind(window.CSS)) || (window.supportsCSS);
    if (!(!!supportsFn && (supportsFn('--f:0') || supportsFn('--f', 0)))) {
        $(".oldbrowser-alert").show()
    }

    // Add CSRF token to ajax requests
    $.ajaxSetup({
        headers: {
            // Disabled for now - problems with DataTables and dynamic language loading
            // "X-CSRF-TOKEN": getCsrfToken()
        }
    });

    // Initialise tooltips and popovers
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    // Display or hide offline admins by default
    $(".admin-status").each(function (key, status) {
        var hide = $(status).data("hidebydefault")

        if (hide) {
            adminStatusDisplayOffline(false)
        }
    })

    // Show / hide offline admins from admin status
    $("[data-adminstatusoffline]").click(function (e) {
        var el = $(this)
        var show = el.data("adminstatusoffline") === "show"
        $("[data-adminstatusoffline]").show()
        el.hide()
        adminStatusDisplayOffline(show)
    })

    function adminStatusDisplayOffline(show) {
        var offlineAdmins = $(".admin-status .status-offline")
        show ? offlineAdmins.show() : offlineAdmins.hide()
    }

    // Functions
    function updateRelativeTime() {
        $('[data-relativetime]').each(function () {
            var el = $(this);
            var mode = el.data("relativetime");
            var timestamp = el.data("timestamp");

            var fulldate = timestampToDate(timestamp, true)
            var fuzzydate = timestampToDate(timestamp, false)

            if (mode == "fuzzydate") {
                el.attr("data-toggle", "tooltip");
                el.attr("title", fulldate);
                el.html(fuzzydate);
            } else if (mode == "fulldate") {
                el.html(fulldate);
            }
        });
    }
    // News edited date
    const newsEditedElements = document.querySelectorAll('.news-edited');
    newsEditedElements.forEach(element => {
    const editedDate = element.getAttribute('data-date');
    const tooltipTitle = `${dayjs(editedDate).format('D MMMM YYYY MM:HH')}`;
    element.setAttribute('data-original-title', tooltipTitle);
    })
});

function timestampToDate(timestamp, full) {
    "use strict"

    var fuzzydate = dayjs.unix(timestamp).fromNow()
    var fulldate = dayjs.unix(timestamp).format("LLL")
    return full ? fulldate : fuzzydate
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}

function updateTooltip(el, text) {
    if ($(el).attr("title")) {
        $(el).attr("title", text)
    }

    $(el).attr("data-original-title", text)

    var id = $(el).attr("aria-describedby")
    if (id) {
        $("#" + id + " .tooltip-inner").text(text)
    }
}

function updateTooltipWithTranslation(el) {
    var args = Array.prototype.slice.call(arguments);
    args.shift()
    updateTooltip(el, "".format.apply(el.data("translation"), args))
}

function copyTextToClipboard(text) {
    var textArea = document.createElement("textarea")
    textArea.style.position = "fixed"
    textArea.style.top = -999999999
    textArea.style.left = -999999999
    textArea.style.opacity = 0
    textArea.value = text
    document.body.appendChild(textArea)
    textArea.select()

    var success = false

    try {
        success = document.execCommand('copy')
    } catch (err) {}

    document.body.removeChild(textArea)
    return success
}
