import os
from dataclasses import dataclass


@dataclass
class AppConfig:
    api_url: str
    api_key: str
    model_name: str
    timeout: float

def load_config() -> AppConfig:
    config = AppConfig(
        api_url=os.environ["LLM_API_URL"],
        api_key=os.environ["LLM_API_KEY"],
        model_name=os.environ["LLM_MODEL_NAME"],
        timeout=float(os.environ["LLM_TIMEOUT"])
    )
    return config