const DatePicker = ({ date, setDate, label }) => {
  return (
    <div class="form-group form-inline">
      {/* <span class="input-group-text">{label}</span> */}
      <label for="floatingInput">{label}</label>
      <input
        id="floatingInput"
        value={date}
        onChange={(e) => setDate(e.target.value)}
        class="form-control form-control-sm"
        name="date"
        type="date"
        required
      />
    </div>
  );
};

export default DatePicker;
