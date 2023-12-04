const CategoryFilter = ({
  categories,
  categoriesData,
  resetSearchStates,
  setCategories,
}) => {
  return categoriesData?.map((cat) => (
    <div key={cat} class="checkbox">
      <label key={cat}>
        <input
          type="checkbox"
          class="icheck"
          value={cat}
          key={cat}
          onChange={(e) => {
            const val = e.target.value;
            resetSearchStates();
            e.target.checked
              ? setCategories([...categories, val])
              : setCategories(categories.filter((cat) => cat !== val));
          }}
          checked={categories.includes(cat) ? "checked" : ""}
        />{" "}
        {cat}
      </label>
    </div>
  ));
};

export default CategoryFilter;
