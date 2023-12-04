import { Select } from "antd";

const SourceFilter = ({
  sourcesData,
  resetSearchStates,
  setSources,
  sources,
}) => {
  return (
    <Select
      mode="multiple"
      style={{ width: "100%" }}
      aria-label="Default select example"
      value={sources ?? []}
      onChange={(e) => {
        resetSearchStates();
        setSources(e);
      }}
      options={sourcesData?.map((src) => ({
        label: src,
        value: src,
      }))}
    />
  );
};

export default SourceFilter;
