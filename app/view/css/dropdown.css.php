<style>

    @media only screen and (max-width: 767px) {
        
        ul {
            color: green
        }
        
        .mobile-menu {
            border: none;
            font-size: 24px;
            background: none;
            width: 30px;
            height: 25px;
            left: 20px;
            top: 10px;
            position: fixed;
            z-index: 1000;
            left: 10px;
        }
        
        .mobile-menu:focus{
            color: green;
        }
        
        .dropdown {
          position: relative;
          display: inline-block;
        }
        
        .dropdown-content {
          display: none;
          position: absolute;
          background-color: white;
          overflow: auto;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 1;
          width: 50vw;
        }
        
        .dropdown-content a {
          color: black;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
        }
        
        .dropdown a:hover {background-color: #ddd;}
        
        .show {display: block;}
    
        .mobile {
            left: 10px;
            top: 50;
        }
    }
    
    @media only screen and (min-width: 768px) {
        .mobile-menu{display: none;}
        .mobile{display: none;}
        
        .desktop-nav{
            position: fixed;
            right: 10px;
            top: 25px;
            width: 50%;
            justify-content: space-evenly;
            align-items: center;
            display: flex;
        }
        
        .desktop-nav{
            position: fixed;
            right: 10px;
            top: 20px;
            width: 50%;
            justify-content: space-evenly;
            align-items: stretch;
            display: flex;
        }
        
        .desktop-nav button {
            border: none;
            background: none;
            padding: 10px 5px 3px 1px;
            font-size: 18px;
        }
        
        .desktop-nav button:hover, .desktop-nav button:focus{
            border-bottom: blue solid 2px;
        }
        
        .dropdown-content {
          display: none;
          position: absolute;
          background-color: white;
          overflow: auto;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 999;
          width: 30%;
        }
        
        .dropdown-content a {
          color: black;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
        }
        
        .dropdown a:hover {background-color: #ddd;}
        
        .show {display: block;}
    }

</style>