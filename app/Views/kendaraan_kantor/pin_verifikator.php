<style>
  form {
    p {
      display: flex;
      align-items: center;
      justify-content: center;

      input {
        cursor: default;
        -webkit-appearance: none;
        appearance: none;
        -moz-appearance: textfield;
        box-sizing: border-box;
        height: 40px;
        width: 40px;
        border-radius: 5px;
        margin: 0 5px;
        text-align: center;
        font-size: 24px;
        caret-color: transparent;
        transition: 0.15s ease-in-out;
        outline: none;
        box-shadow: inset 0 0 0 0 transparent;
        transform: perspective(500px) rotateX(0) rotateY(0);

        &:focus {
          box-shadow: inset 0 0 0 3px #1373f3;
        }

        &::-webkit-outer-spin-button,
        &::-webkit-inner-spin-button {
          -webkit-appearance: none;
          appearance: none;
          margin: 0;
        }

        &:valid {
          background-image: radial-gradient(white 7px, transparent 8px);
        }
      }
    }
  }

  #showCodePin {
    display: none;

    &:checked {
      input {
        transition: 0.15s;
        animation: reveal 0.3s ease-in-out;

        &:nth-child(1) {
          animation-delay: 0;
        }

        &:nth-child(2) {
          animation-delay: 0.05s;
        }

        &:nth-child(3) {
          animation-delay: 0.1s;
        }

        &:nth-child(4) {
          animation-delay: 0.15s;
        }

        background-image: none;

        &:focus::selection {
          color: white;
        }
      }
    }
  }

  @keyframes reveal {
    0% {
      transform: perspective(500px) rotateX(0) rotateY(0) scale(-1, 1);
      color: transparent;
    }

    66% {
      transform: perspective(500px) rotateY(0deg) rotateY(90deg) scale(-1, 1);
      color: transparent;
      background-color: transparent;
    }

    100% {
      transform: perspective(500px) rotateY(0deg) rotateY(180deg) scale(-1, 1);
      color: white;
      background-color: transparent;
    }
  }

  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
</style>


<form id="codePinForm" class="text-left" onsubmit="handleSubmit(event)">
  <input type="checkbox" id="showCodePin" checked style="display: none;" />
  <p>
    <input type="number" required id="pin_1" autocomplete="off" oninput="handleInput(event)" onkeydown="handleKeydown(event)" onclick="handleClick(event)" class="input-field-pin">
    <input type="number" required id="pin_2" autocomplete="off" oninput="handleInput(event)" onkeydown="handleKeydown(event)" onclick="handleClick(event)" class="input-field-pin">
    <input type="number" required id="pin_3" autocomplete="off" oninput="handleInput(event)" onkeydown="handleKeydown(event)" onclick="handleClick(event)" class="input-field-pin">
    <input type="number" required id="pin_4" autocomplete="off" oninput="handleInput(event)" onkeydown="handleKeydown(event)" onclick="handleClick(event)" class="input-field-pin">
  </p>
</form>