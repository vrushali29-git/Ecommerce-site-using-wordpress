(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCountDownHandler = function ($scope, $) {
        var kataCountdown = $scope.find('.kata-countdown'),
            kataCountdowntime = $scope.find('.kata-countdown-time'),
            type = kataCountdown.data('type'),
            date = kataCountdown.data('date'),
            wmd = kataCountdown.data('wmd'),
            month = kataCountdown.data('month'),
            week = kataCountdown.data('week'),
            day = kataCountdown.data('day'),
            hour = kataCountdown.data('hour'),
            minute = kataCountdown.data('minute'),
            second = kataCountdown.data('second'),
            message = kataCountdown.data('message');

        kataCountdown.each(function () {
            switch (type) {
                case 'default':
                    kataCountdowntime.countdown(date, function (event) {
                            var $this = $(this).html(event.strftime('' +
                                '<span class="day-wrapper"><span class="countdown-num day-num">%D</span> <span class="countdown-h day-h">' + day + '</span></span>' +
                                '<span class="hour-wrapper"><span class="countdown-num hour-num">%H</span> <span class="countdown-h hour-h">' + hour + '</span></span>' +
                                '<span class="minute-wrapper"><span class="countdown-num minute-num">%M</span> <span class="countdown-h minute-h">' + minute + '</span></span>' +
                                '<span class="second-wrapper"><span class="countdown-num second-num">%S</span> <span class="countdown-h second-h">' + second + '</span></span>'));
                        })

                        .on('finish.countdown', function (event) {
                            $(this).html(message)
                                .parent().addClass('disabled');
                        });
                    break;
                case 'advanced':
                    kataCountdowntime.countdown(date)
                        .on('update.countdown', function (event) {
                            var format = '%H:%M:%S';
                            if (event.offset.totalDays > 0) {
                                format = '<span class="day-wrapper"><span class="countdown-num day-num">%-d</span> <span class="countdown-h day-h">' + day + '</span></span> ' + '<span class="time-wrapper"><span class="countdown-num time-num">' + format + '</span></span>';
                            }
                            if (event.offset.weeks > 0) {
                                format = '<span class="week-wrapper"><span class="countdown-num week-num">%-w</span> <span class="countdown-h week-h">' + week + '</span></span> ' + format;
                            }
                            $(this).html(event.strftime('<span class="countdown-advance">' + format + '</span>'));
                        })

                        .on('finish.countdown', function (event) {
                            $(this).html(message)
                                .parent().addClass('disabled');
                        });
                    break;

                case 'basic':
                    kataCountdowntime.countdown(date, function (event) {
                            $(this).html(event.strftime('<span class="day-wrapper"><span class="countdown-num day-num">%D</span> <span class="countdown-h day-h">' + day + '</span></span> <span class="time-wrapper"><span class="countdown-num time-num">%H:%M:%S</span></span>'));
                        })

                        .on('finish.countdown', function (event) {
                            $(this).html(message)
                                .parent().addClass('disabled');
                        });
                    break;

                case 'legacy_style':
                    kataCountdowntime.countdown(date, function (event) {
                            var $this = $(this).html(event.strftime('' +
                                '<span class="week-wrapper"><span class="countdown-num week-num">%w</span> <span class="countdown-h week-h">' + week + '</span></span>' +
                                '<span class="day-wrapper"><span class="countdown-num day-num">%d</span> <span class="countdown-h day-h">' + day + '</span></span>' +
                                '<span class="hour-wrapper"><span class="countdown-num hour-num">%H</span> <span class="countdown-h hour-h">' + hour + '</span></span>' +
                                '<span class="minute-wrapper"><span class="countdown-num minute-num">%M</span> <span class="countdown-h minute-h">' + minute + '</span></span>' +
                                '<span class="second-wrapper"><span class="countdown-num second-num">%S</span> <span class="countdown-h second-h">' + second + '</span></span>'));
                        })

                        .on('finish.countdown', function (event) {
                            $(this).html(message)
                                .parent().addClass('disabled');
                        });
                    break;

                case 'mawo':
                    kataCountdowntime.countdown(date, function (event) {
                        switch (wmd) {
                            case 'weeks':
                                kataCountdowntime.html(event.strftime('<span class="week-wrapper"><span class="countdown-num week-num">%w</span> <span class="countdown-h week-h">' + week + ' and</span></span> <span class="day-wrapper"><span class="countdown-num day-num">%d</span> <span class="countdown-h day-h">' + day + '</span></span>'));
                                break;

                            case 'months':
                                kataCountdowntime.html(event.strftime('<span class="month-wrapper"><span class="countdown-num month-num">%m</span> <span class="countdown-h month-h">' + month + ' and</span></span> <span class="day-wrapper"><span class="countdown-num day-num">%n</span> <span class="countdown-h day-h">' + day + '</span></span>'));
                                break;

                            case 'days':
                                kataCountdowntime.html(event.strftime('<span class="day-wrapper"><span class="countdown-num day-num">%D</span> <span class="countdown-h day-h">' + day + '</span></span>'));
                                break;

                            default:
                                kataCountdowntime.html(event.strftime('<span class="day-wrapper"><span class="countdown-num day-num">%D</span> <span class="countdown-h day-h">' + days + '</span></span>'));
                                break;
                        }
                    });
                    break;

                case 'sothr':
                    kataCountdowntime.countdown(date, function (event) {
                            var totalHours = event.offset.totalDays * 24 + event.offset.hours;
                            $(this).html(event.strftime('<span class="hour-wrapper"><span class="countdown-num hour-num">' + totalHours + '</span>' + ' <span class="countdown-h hour-h">' + hour + '</span></span> <span class="minute-wrapper"><span class="countdown-num minute-num">%M</span> <span class="countdown-h minute-h">' + minute + '</span></span> <span class="second-wrapper"><span class="countdown-num second-num"> %S </span><span class="countdown-h second-h">' + second + '</span></span>'));
                        })

                        .on('finish.countdown', function (event) {
                            $(this).html(message)
                                .parent().addClass('disabled');
                        });
                    break;

                default:
                    kataCountdowntime.countdown(date)
                        .on('update.countdown', function (event) {
                            var format = '%H:%M:%S';
                            if (event.offset.totalDays > 0) {
                                format = '<span class="day-wrapper"><span class="countdown-num day-num">%-d</span> <span class="countdown-h day-h">' + day + '</span></span> ' + '<span class="time-wrapper"><span class="countdown-num time-num">' + format + '</span></span>';
                            }
                            if (event.offset.weeks > 0) {
                                format = '<span class="week-wrapper"><span class="countdown-num week-num">%-w</span> <span class="countdown-h week-h">' + week + '</span></span> ' + format;
                            }
                            $(this).html(event.strftime(format));
                        })

                        .on('finish.countdown', function (event) {
                            $(this).html(message)
                                .parent().addClass('disabled');
                        });
                    break;
            }
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-countdown.default', WidgetCountDownHandler);
    });
})(jQuery);