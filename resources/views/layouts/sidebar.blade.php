 <div class="sidebar" data-background-color="dark">
     <div class="sidebar-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             <a href="index.html" class="logo">
                 <img src="/assets/image/p.png" alt="navbar brand" class="navbar-brand" height="50" />
                 <h5 class="text-white m-2">AdminSystem</h5>
             </a>
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
     </div>
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <ul class="nav nav-secondary">
                 <li class="nav-item active">
                     <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                         <i class="fas fa-home"></i>
                         <p>HomeDashborad</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="dashboard">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="{{ route('customers.index') }}">
                                     <span class="sub-item">Customer</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>
                 <li class="nav-section">
                     <span class="sidebar-mini-icon">
                         <i class="fa fa-ellipsis-h"></i>
                     </span>
                     <h4 class="text-section">Aorchor</h4>
                 </li>
                 <li class="nav-item">
                     <a data-bs-toggle="collapse" href="#base">
                         <i class="fas fa-layer-group"></i>
                         <p>Base</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="base">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="{{ route('Category.index') }}">
                                     <span class="sub-item">Categories</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>
                 <li class="nav-item">
                     <a data-bs-toggle="collapse" href="#maps">
                         <i class="fas fa-layer-group"></i>
                         <p>Poster</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="maps">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="maps/googlemaps.html">
                                     <span class="sub-item">AllPoster</span>
                                 </a>
                             </li>
                             <li>
                                 <a href="maps/jsvectormap.html">
                                     <span class="sub-item">CreatePoster</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>
                 <li class="nav-item">
                     <a data-bs-toggle="collapse" href="#charts">
                         <i class="far fa-chart-bar"></i>
                         <p>Charts</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="charts">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="charts/charts.html">
                                     <span class="sub-item">Chart Js</span>
                                 </a>
                             </li>
                             <li>
                                 <a href="charts/sparkline.html">
                                     <span class="sub-item">Sparkline</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li>
                 <li class="nav-item">
                     <a href="widgets.html">
                         <i class="fas fa-desktop"></i>
                         <p>Widgets</p>
                         <span class="badge badge-success">4</span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="#">
                         <i class="fas fa-file"></i>
                         <p>Documentation</p>
                         <span class="badge badge-secondary">1</span>
                     </a>
                 </li>

             </ul>
         </div>
     </div>
 </div>
