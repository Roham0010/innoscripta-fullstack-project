export const handleErrors = (error, setError) => {
  console.log(error);
  if (error?.response?.status === 422) {
    setError(error.response.data.message);
    return;
  }

  setError(error.message);
};
