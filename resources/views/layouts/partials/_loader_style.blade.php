<style>

  /**
   * ==============================================
   * Dot Flashing
   * ==============================================
   */
   .dot-flashing {
    position: relative;
    width: 10px;
    height: 10px;
    border-radius: 5px;
    background-color: #ffc480;
    color: #ffc480;
    animation: dot-flashing .48s infinite linear alternate;
    animation-delay: 0.24s;
  }
  .dot-flashing::before, .dot-flashing::after {
    content: "";
    display: inline-block;
    position: absolute;
    top: 0;
  }
  .dot-flashing::before {
    left: -15px;
    width: 10px;
    height: 10px;
    border-radius: 5px;
    background-color: #ffc480;
    color: #ffc480;
    animation: dot-flashing .48s infinite alternate;
    animation-delay: 0s;
  }
  .dot-flashing::after {
    left: 15px;
    width: 10px;
    height: 10px;
    border-radius: 5px;
    background-color: #ffc480;
    color: #ffc480;
    animation: dot-flashing .48s infinite alternate;
    animation-delay: .48s;
  }
  
  @keyframes dot-flashing {
    0% {
      background-color: #ffc480;
    }
    50%, 100% {
      background-color: rgba(255, 185, 128, 0.2);
    }
  }
    </style>