<?php

    namespace chlog;
    
    class About_View extends ChlogView {

        public function __construct() {}
        
        public function html() {
            return <<<HTML

            <h2>Welcome to chLOG</h2>
            <p>
                chLOG is a multi-device web application specifically for tracking Cluster Headaches.
            </p>
            <p>
                chLOG has been created by Mark Tonks, a long time Cluster Headache battler, to fill
                the void of specific, dedicated applications for recording and reporting on this 
                unusual and extreme medical condition. 
            </p>
            <p>
                Cluster Headaches can present with a variety of symptoms and is treated with a 
                broad range of medications at varying dosages. Treatment plans are tailored to the 
                individual and often change from day-to-day, or week-to-week. 
            </p>
            <p>
                Many people with Cluster Headaches record their attacks either for their own reference
                or to assist their medical advisers in diagnosis or improved treatment plans. 
                Often these records are on paper, or a simple spreadsheet. 
            </p>
            <p>
                chLOG is a project to make the process of tracking clusters simpler and more consistent,
                and to allow greater insight to be gained into an individual's cluster patterns.
            </p>
            <p>
                chLOG is a web based application designed to run on any device, from a small-screen
                smartphone to a large widescreen monitor. chLOG is free to use and free from 
                advertising. 
            </p>
            <p>
                <strong>
                    chLOG will never ask you for any personally identifiable information.
                </strong>
            </p>
            <p>
                All that is required to register is a valid email address and a password.
            </p>
HTML;
        }
        
        public function title() {
            return "chLOG About Page";   
        }
        
        public function css() {
            return "/about/chlog-about.css";   
        }
    }
