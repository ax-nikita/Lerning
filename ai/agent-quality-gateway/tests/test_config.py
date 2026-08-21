from agent_quality_gateway.config import load_config


def test_load_config_from_environment(monkeypatch) -> None:
    monkeypatch.setenv("LLM_API_URL", "url/random")
    monkeypatch.setenv("LLM_API_KEY", "dwadawdawda")
    monkeypatch.setenv("LLM_MODEL_NAME", "qwen")
    monkeypatch.setenv("LLM_TIMEOUT", "30.0")
    config = load_config()
    assert config.timeout == 30.0
    assert config.model_name == "qwen"
    assert config.api_key == "dwadawdawda"
    assert config.api_url == "url/random"