window.onload = async () => {
  const rootElement = document.querySelector("#root");

  try {
    const res = await fetch("http://localhost:8000");
    const data = await res.json();

    data.map((user) => {
      const userElement = document.createElement("div");
      userElement.innerText = `${user.name} ${user.firstname}`;
      rootElement.appendChild(userElement);
    });
  } catch (err) {
    console.error(err);
  }
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
