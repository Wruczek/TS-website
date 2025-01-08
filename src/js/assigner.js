$(function () {
    "use strict"

    recalculateGroups()

    $(".group-assigner .list-group-item:not(.assigner-header)").click(function (e) {
        var checkbox = $(this).find("input[type=checkbox]")
        $(checkbox).prop("checked", !checkbox.is(':checked'))
        recalculateGroups()
    })

    $(".group-assigner input[type=checkbox]").change(function (e) {
        recalculateGroups()
    })

    function recalculateGroups() {
        var invalidGroups = false
        var assignerCategories = $(".group-assigner .assigner-category")

        assignerCategories.each(function (key, val) {
            var assignerCategory = $(val)
            var maxGroups = assignerCategory.data("maxgroups")
            var usedGroups = assignerCategory.find(":checkbox:checked").length
            var badge = assignerCategory.find(".assigner-header .badge")
            var isValid = usedGroups <= maxGroups

            badge.text(usedGroups + " / " + maxGroups)

            if (isValid) {
                badge.removeClass("badge-invalid")
            } else {
                badge.addClass("badge-invalid")
                invalidGroups = true
            }

            if (key === assignerCategories.length - 1) {
                // last iteration! update the "save" button state
                $(".group-assigner .assigner-save").prop("disabled", invalidGroups)
                $(".group-assigner .invalid-groups-alert").css("display", invalidGroups ? "inline-block" : "none")
            }
        })
    }
})

function startCooldownTimer(duration) {
    var timer = duration, minutes, seconds;
    var cooldownElement = document.getElementById('cooldown-timer');
    if (!cooldownElement) return;

    function updateTimer() {
        minutes = parseInt(timer / 60);
        seconds = parseInt(timer % 60);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        cooldownElement.textContent = minutes + ":" + seconds;
    }

    updateTimer();

    var interval = setInterval(function () {
        updateTimer();
    
        if (--timer < 0) {
            clearInterval(interval);
            location.reload();
        }
    }, 1000);
}

startCooldownTimer(cooldownRemaining);