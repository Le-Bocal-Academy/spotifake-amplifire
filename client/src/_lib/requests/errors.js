export default {
  constructor: (responseJson) => {
    const errors = responseJson["errors"];
    let errorMessage = "";
    Object.keys(errors).forEach((key) => {
      const errorMessages = errors[key];
      errorMessage += `${key}: `;
      errorMessages.forEach((message) => {
        errorMessage += `${message}\n`;
      });
    });
    return errorMessage;
  },
};
