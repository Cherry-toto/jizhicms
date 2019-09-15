	<title>极致建站系统安装向导</title>
    <link rel="icon" href="">
    <link rel="stylesheet" href="./tpl/css/bootstrap.min.css" crossorigin="anonymous" />
	<script type="text/javascript" src="./tpl/js/jquery.min.js"></script>
	<script type="text/javascript" src="./tpl/layer/layer.min.js"></script>
	<script type="text/javascript" src="./tpl/js/common.js"></script>
	<link rel="stylesheet" href="./tpl/css/ui.progress-bar.css">
    <style>
		/*進度條樣式*/
              .progress{
                  height: 25px;
                  background: #262626;
                  padding: 1px;
                  overflow: visible;
                  border-radius: 20px;
                  border-top: 1px solid #000;
                  border-bottom: 1px solid #7992a8;
              }
              .progress .progress-bar{
                  border-radius: 20px;
                  position: relative;
                  animation: animate-positive 2s;
              }
              .progress .progress-value{
                  display: block;
                  padding: 3px 7px;
                  font-size: 13px;
                  color: #fff;
                  border-radius: 4px;
                  background: #191919;
                  border: 1px solid #000;
                  position: absolute;
                  top: -40px;
                  right: -10px;
              }
              .progress .progress-value:after{
                  content: "";
                  border-top: 10px solid #191919;
                  border-left: 10px solid transparent;
                  border-right: 10px solid transparent;
                  position: absolute;
                  bottom: -6px;
                  left: 26%;
              }
              .progress-bar.active{
                  animation: reverse progress-bar-stripes 0.40s linear infinite, animate-positive 2s;
              }
              @-webkit-keyframes animate-positive{
                  0% { width: 0; }
              }
              @keyframes animate-positive{
                  0% { width: 0; }
              }
			  
		/*分步樣式*/
			*, *::after, *::before {
			  box-sizing: border-box;
			}

			a {
			  color: #96c03d;
			  text-decoration: none;
			}

			.cd-breadcrumb {
			  width: 90%;
			  max-width: 768px;
			  padding: 0.5em 1em;
			  margin: 1em auto;
			  background-color: #edeff0;
			  border-radius: .25em;
			}
			.cd-breadcrumb:after {
			  content: "";
			  display: table;
			  clear: both;
			}
			.cd-breadcrumb li {
			  display: inline-block;
			  float: left;
			  margin: 0.5em 0;
			}
			.cd-breadcrumb li::after {
			  display: inline-block;
			  content: '\00bb';
			  margin: 0 .6em;
			  color: #959fa5;
			}
			.cd-breadcrumb li:last-of-type::after {
			  display: none;
			}
			.cd-breadcrumb li > * {
			  display: inline-block;
			  font-size: 1.4rem;
			  color: #2c3f4c;
			}
			.cd-breadcrumb li.current > * {
			  color: #96c03d;
			}
			.cd-breadcrumb.custom-icons li > *::before {
			  content: '';
			  display: inline-block;
			  height: 20px;
			  width: 20px;
			  margin-right: .4em;
			  margin-top: -2px;
			  background: url(../img/cd-custom-icons-01.svg) no-repeat 0 0;
			  vertical-align: middle;
			}
			.cd-breadcrumb.custom-icons li:not(.current):nth-of-type(2) > *::before {
			  background-position: -20px 0;
			}
			.cd-breadcrumb.custom-icons li:not(.current):nth-of-type(3) > *::before {
			  background-position: -40px 0;
			}
			.cd-breadcrumb.custom-icons li.current:nth-of-type(4) > *::before {
			  background-position: -60px -20px;
			}
			@media only screen and (min-width: 768px) {
			  .cd-breadcrumb {
				padding: 0 1.2em;
			  }
			  .cd-breadcrumb li {
				margin: 1.2em 0;
			  }
			  .cd-breadcrumb li::after {
				margin: 0 1em;
			  }
			  .cd-breadcrumb li > * {
				font-size: 1rem;
			  }
			}

			@media only screen and (min-width: 768px) {
			  .cd-breadcrumb.triangle {
				background-color: transparent;
				padding: 0;
			  }
			  .cd-breadcrumb.triangle li {
				position: relative;
				padding: 0;
				margin: 4px 4px 4px 0;
			  }
			  .cd-breadcrumb.triangle li:last-of-type {
				margin-right: 0;
			  }
			  .cd-breadcrumb.triangle li > * {
				position: relative;
				padding: 1em .8em 1em 2.5em;
				color: #2c3f4c;
				background-color: #edeff0;
				border-color: #edeff0;
			  }
			  .cd-breadcrumb.triangle li.current > * {
				color: #ffffff;
				background-color: #96c03d;
				border-color: #96c03d;
			  }
			  .cd-breadcrumb.triangle li:first-of-type > * {
				padding-left: 1.6em;
				border-radius: .25em 0 0 .25em;
			  }
			  .cd-breadcrumb.triangle li:last-of-type > * {
				padding-right: 1.6em;
				border-radius: 0 .25em .25em 0;
			  }
			  .cd-breadcrumb.triangle li::after, .cd-breadcrumb.triangle li > *::after {
				content: '';
				position: absolute;
				top: 0;
				left: 100%;
				content: '';
				height: 0;
				width: 0;
				border: 24px solid transparent;
				border-right-width: 0;
				border-left-width: 20px;
			  }
			  .cd-breadcrumb.triangle li::after {
				z-index: 1;
				-webkit-transform: translateX(4px);
				-moz-transform: translateX(4px);
				-ms-transform: translateX(4px);
				-o-transform: translateX(4px);
				transform: translateX(4px);
				border-left-color: #ffffff;

				margin: 0;
			  }
			  .cd-breadcrumb.triangle li > *::after {
				z-index: 2;
				border-left-color: inherit;
			  }
			  .cd-breadcrumb.triangle li:last-of-type::after, .cd-breadcrumb.triangle li:last-of-type > *::after {
				display: none;
			  }
			  .cd-breadcrumb.triangle.custom-icons li::after, .cd-breadcrumb.triangle.custom-icons li > *::after {
				border-top-width: 25px;
				border-bottom-width: 25px;
			  }

			  @-moz-document url-prefix() {
				.cd-breadcrumb.triangle li::after,
				.cd-breadcrumb.triangle li > *::after {
				  border-left-style: dashed;
				}
			  }
			}

			@media only screen and (min-width: 768px) {
			  .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(4) em::before {
				background-position: -60px -40px;
			  }
			}

		blockquote {
		    padding: 1.25rem;
		    margin-top: 1.25rem;
		    margin-bottom: 1.25rem;
		    border: 1px solid #eee;
		    border-left-width: .25rem;
		    border-left-color: #5bc0de;
		    border-radius: .25rem;
		}
    	.card {border:none;}
    	.footer {padding:40px 0;background-color:#fff;color:#4b4c4d;text-align:center;}


    </style>
	