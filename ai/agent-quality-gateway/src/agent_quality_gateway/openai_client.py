import httpx

from agent_quality_gateway.exceptions import TransportError


class OpenAICompatibleClient:
    api_url: str
    api_key: str
    model: str
    timeout: httpx.Timeout
    transport: httpx.AsyncBaseTransport | None

    def __init__(
            self,
            api_url: str,
            api_key: str,
            model: str,
            timeout: httpx.Timeout,
            transport: httpx.AsyncBaseTransport | None = None,
    ) -> None:
        self.api_url = api_url
        self.api_key = api_key
        self.model = model
        self.timeout = timeout
        self.transport = transport

    async def generate(self, prompt: str) -> str:
        async with httpx.AsyncClient(
                transport=self.transport,
                timeout=self.timeout
        ) as client:
            endpoint = f"{self.api_url}/v1/chat/completions"
            headers = {
                "Authorization": f"Bearer {self.api_key}"
            }
            payload = {
                "model": self.model,
                "messages": [
                    {
                        "role": "user",
                        "content": prompt,
                    }
                ],
            }
            response = await client.post(
                endpoint,
                headers=headers,
                json=payload
            )

            try:
                response.raise_for_status()
                return self._extract_content(response)
            except httpx.HTTPStatusError as error:
                raise TransportError(
                    f"HTTP {error.response.status_code}: {error.response.text}"
                ) from error

    def _extract_content(self, response: httpx.Response) -> str:
        return response.json()["choices"][0]["message"]["content"]