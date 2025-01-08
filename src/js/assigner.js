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

    var cooldownElement = $("#cooldown-timer");

    if (cooldownElement.length) {
        var secondsLeft = cooldownRemaining;

        function updateTimer() {
            var minutes = Math.floor(secondsLeft / 60).toString().padStart(2, "0");
            var seconds = Math.floor(secondsLeft % 60).toString().padStart(2, "0");
            cooldownElement.text(minutes + ":" + seconds);
        }

        updateTimer();

        var interval = setInterval(function () {
            updateTimer();

            if (--secondsLeft < 0) {
                clearInterval(interval);
                location.reload();
            }
        }, 1000);
    }
})
