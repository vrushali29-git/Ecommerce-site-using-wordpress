(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var runtime = true;
    var KataParallaxMotion = function ($scope, $) {
        !(function (u) {
            "use strict";
            u.fn.enllax = function (t) {
                var d = u(window).height(),
                    x = u(document).height(),
                    f = u.extend({ ratio: 0, type: "background", direction: "vertical" }, t);
                u("[data-enllax-ratio]").each(function () {
                    var r = u(this),
                        a = r.offset().top,
                        n = r.outerHeight(),
                        t = r.data("enllax-ratio"),
                        o = r.data("enllax-type"),
                        e = r.data("enllax-direction"),
                        s = t || f.ratio,
                        i = o || f.type,
                        c = e || f.direction,
                        l = Math.round(a * s),
                        p = Math.round((a - d / 2 + n) * s);
                    "background" == i
                        ? "vertical" == c
                            ? r.css({ "background-position": "center " + -l + "px" })
                            : "horizontal" == c && r.css({ "background-position": -l + "px center" })
                        : "foreground" == i &&
                          ("vertical" == c
                              ? r.css({
                                    "-webkit-transform": "translateY(" + p + "px)",
                                    "-moz-transform": "translateY(" + p + "px)",
                                    transform: "translateY(" + p + "px)",
                                })
                              : "horizontal" == c &&
                                r.css({
                                    "-webkit-transform": "translateX(" + p + "px)",
                                    "-moz-transform": "translateX(" + p + "px)",
                                    transform: "translateX(" + p + "px)",
                                })),
                        u(window).on("scroll", function () {
                            var t = u(this).scrollTop();
                            (l = Math.round((a - t) * s)),
                                (p = Math.round((a - d / 2 + n - t) * s)),
                                "background" == i
                                    ? "vertical" == c
                                        ? r.css({ "background-position": "center " + -l + "px" })
                                        : "horizontal" == c &&
                                          r.css({ "background-position": -l + "px center" })
                                    : "foreground" == i &&
                                      t < x &&
                                      ("vertical" == c
                                          ? r.css({
                                                "-webkit-transform": "translateY(" + p + "px)",
                                                "-moz-transform": "translateY(" + p + "px)",
                                                transform: "translateY(" + p + "px)",
                                            })
                                          : "horizontal" == c &&
                                            r.css({
                                                "-webkit-transform": "translateX(" + p + "px)",
                                                "-moz-transform": "translateX(" + p + "px)",
                                                transform: "translateX(" + p + "px)",
                                            }));
                        });
                });
            };
        })(jQuery);

        // on Scroll
        $(".kata_parallax").enllax();

        // on mouseMove
        !function () {
            "use strict";
            var elementClass = ".kata-mouse-parallax";
            /**
             * IE lt 9 detection
             */
            var msie =
                /MSIE.\d+\./gi.test(navigator.userAgent) &&
                +navigator.userAgent.replace(/.*MSIE.(\d+)\..*/gi, "$1") < 9;

            /**
             * Event name
             */
            var eventName =
                "ontouchstart" in window || "onmsgesturechange" in window ? "touchmove" : "mousemove";

            /**
             * Pause flag
             */
            var pause;

            /**
             * Effects
             */
            var effects = ["x", "y", "scale", "opacity", "rotate"];

            /**
             * Transform map
             */
            var transformMap = {
                "webkitTransform": "translate3d",
                "MozTransform": "translate3d",
                "msTransform": "translate3d",
                "OTransform": "translate",
                "transform": "translate3d",
            };

            /**
             * Anm
             * @api public
             */
            function anm() {}

            /**
             * Elements
             * @api public
             */
            var elements = (anm.elements = document.querySelectorAll(elementClass));

            /**
             * On animation
             * @api public
             */
            anm.on = function () {
                pause = false;
            };

            /**
             * Off animation
             * @api public
             */
            anm.off = function () {
                pause = true;
            };

            /**
             * Toggle animation
             * @api public
             */
            anm.toggle = function () {
                pause = !pause;
            };

            /**
             * Calculate cursor position
             * @param  {Object} e
             * @return {Object}
             * @api private
             */
            function calculatePosition(e) {
                var pos = {};
                pos.x = e.clientX - window.innerWidth / 2 || 0;
                pos.y = e.clientY - window.innerHeight / 2 || 0;
                pos.fi =
                    Math.atan(pos.x === 0 ? Infinity : -pos.y / pos.x) +
                    (pos.x < 0 ? Math.PI : -pos.y < 0 ? 2 * Math.PI : 0);
                pos.s = (45 * Math.sin(2 * pos.fi)) / 100;
                pos.x /= 100;
                pos.y /= 100;
                pos.r =
                    (Math.sqrt(Math.pow(pos.x, 2) + Math.pow(pos.y, 2)) /
                        Math.sqrt(Math.pow(window.innerWidth, 2) + Math.pow(window.innerHeight, 2))) *
                    2;
                return pos;
            }

            /**
             * Calculate element factors
             * @param  {Object} el
             * @return {Object}
             * @api private
             */
            function calculateFactors(el) {
                var fact = {};
                for (var i = 0; i < effects.length; i++) {
                    fact[effects[i]] = +el.getAttribute("data-speed-" + effects[i]) || 0;
                }
                return fact;
            }

            /**
             * Calculate element transforms
             * @param  {Object} pos
             * @param  {Object} fact
             * @return {Object}
             * @api private
             */
            function calculateTransforms(pos, fact) {
                var transform = {};
                transform.x = pos.x * fact.x + "px";
                transform.y = pos.y * fact.y + "px";
                transform.scale = 1 + pos.r * fact.scale;
                transform.opacity = 1 - pos.r * Math.abs(fact.opacity);
                transform.rotate = -pos.s * pos.r * 100 * fact.rotate + "deg";
                return transform;
            }

            /**
             * Set element transform styles
             * @param  {Object} el
             * @param  {Object} transform
             * @return {Object}
             * @api private
             */
            function setElementStyle(el, transform) {
                if (msie) {
                    el.style.marginLeft = transform.x;
                    el.style.marginTop = transform.y;
                } else {
                    for (var m in transformMap) {
                        if (transformMap.hasOwnProperty(m)) {
                            el.style[m] = [
                                transformMap[m],
                                "(",
                                transform.x + "," + transform.y,
                                transformMap[m] === "translate" ? "" : ",0",
                                ") scale(",
                                transform.scale,
                                ") rotate(",
                                transform.rotate,
                                ")",
                            ].join("");
                        }
                    }

                    el.style.opacity = transform.opacity;
                    el.style.MozOpacity = transform.opacity;
                }
            }

            /**
             * Set elements positions
             * @api private
             */
            function position(e) {
                if (pause) {
                    return;
                }

                e = e.type === "touchmove" ? e.changedTouches[0] : e;
                var fact,
                    transform,
                    pos = calculatePosition(e);

                for (var i = 0, el; i < elements.length; i++) {
                    el = elements[i];
                    fact = calculateFactors(el);
                    transform = calculateTransforms(pos, fact);
                    setElementStyle(el, transform);
                }
            }

            /**
             * Set start position
             */
            position({});

            /**
             * Set move event handler
             */
            if (window.addEventListener) {
                window.addEventListener(eventName, position, false);
            } else {
                window.attachEvent("onmousemove", function () {
                    position.call(window, window.event);
                });
            }

            /**
             * Module exports
             */
            if (typeof define === "function" && define.amd) {
                define([], function () {
                    return anm;
                });
            } else if (typeof module !== "undefined" && module.exports) {
                module.exports = anm;
            } else {
                this.anm = anm;
            }
        }.call(this);
    };

    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-text.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-testimonials.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-shape.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-title.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-image.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-button.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-icon-box.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-icon.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-video-player.default",
            KataParallaxMotion
        );
        // bottom have not in config.php
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-list.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-accordion-toggle.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-address.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-archive-posts.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-author-box.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-author-page.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-banner.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-blog-posts.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-book-table.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-brands.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-breadcrumbs.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-carousel-grid.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-cart.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-categories-list.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-comparison-slider.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-content-slider.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-content-toggle.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-countdown.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-counter.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-date.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-divider.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-domain-checker.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-employee-information.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-food-menu.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-food-menu-toggle.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-gift-cards.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-googlemap.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-grid.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-hamburger-menu.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-image-carousel.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-image-hover-zoom.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-instagram.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-language-switcher.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-login.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-logo.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-masonry-grid.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-menu-navigation.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-next-previous-post.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-phone.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-post-comments.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-post-content.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-post-featured-image.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-post-metadata.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-post-title.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-pricing-plan.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-pricing-table.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-progress-bar.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-recipes.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-related-posts.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-search.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-search-page.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-seo-analytic.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-single-testimonials.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-social-share.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-socials.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-subscribe.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-table.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-tabs.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-task-process.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-team-member.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-testimonials-vertical.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-timeline.default",
            KataParallaxMotion
        );
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-toggle-sidebox.default",
            KataParallaxMotion
        );
    });
})(jQuery);
