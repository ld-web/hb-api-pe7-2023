window.onload = async () => {
  const rootElement = document.querySelector("#root");
  const userForm = document.getElementById("user-form");
  const message = document.getElementById("message");

  try {
    const res = await fetch("http://localhost:8000/users");
    const data = await res.json();

    data.map((user) => {
      const userElement = document.createElement("div");
      userElement.innerText = `${user.last_name} ${user.first_name}`;
      rootElement.appendChild(userElement);
    });
  } catch (err) {
    console.error(err);
  }

  userForm.onsubmit = async (e) => {
    e.preventDefault();
    const values = e.target.elements;
    const name = values["name"].value;
    const firstname = values["firstname"].value;
    const email = values["email"].value;
    const password = values["password"].value;

    try {
      const response = await fetch("http://localhost:8000/users", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name,
          firstname,
          email,
          password,
        }),
      });

      if (response.status >= 400) {
        throw new Error();
      }

      message.style = "color: green";
      message.innerText = "Merci, votre inscription a été effectuée";
    } catch (err) {
      console.error(err);
      message.style = "color: red";
      message.innerText = "Une erreur est survenue lors de l'inscription";
    }
  };
};

// window.onload = () => {
//   const rootElement = document.querySelector("#root");

//   fetch("http://localhost:8000")
//     .then((res) => res.json())
//     .then((data) => {
//       data.map((user) => {
//         const userElement = document.createElement("div");
//         userElement.innerText = `${user.name} ${user.firstname}`;
//         rootElement.appendChild(userElement);
//       });
//     })
//     .catch((err) => console.error(err));
// };
